#!/usr/bin/env perl

use strict;
use DBI;
use DBD::Pg;
use Config::General;
use POSIX qw( strftime );
use Getopt::Std;
use Net::FTP;
use Date::Parse;
use DateTime;
use DateTime::Format::Strptime;
use Time::Piece;
use File::Basename;
use Log::Minimal;
use Email::MIME;
use Email::Sender::Simple qw(sendmail);
use Email::Sender::Transport::SMTP;
use Email::Sender::Transport::SMTP::TLS;
use Config::IniFiles;
use File::Basename;
use Cwd qw(getcwd);
#use Redis::hiredis;
use JSON qw/encode_json decode_json/;
use Data::Dumper;

my $shell_pid = "$$";
my $start_log = 0;
my $start_pid = 0;
my $start_cmd = "$^X $0 @ARGV";
my %opts;
&init();

#my $conf = new Config::General( $opts{c} );
#my %config = $conf->getall;
my %config;
tie %config, 'Config::IniFiles',
  ( -file => $opts{c}, -handle_trailing_comment => 1 );
for my $k1 ( keys %config ) {
    for my $k2 ( keys %{ $config{$k1} } ) {
        if ( $config{$k1}{$k2} =~ /(.*)\.\$(.*)/ ) {
            $config{$k1}{$k2} = $config{$1}{$2};
        }
    }
}
my $log_dir = getcwd().'/storage/';

if ( !-d $log_dir ) {
    if ( !mkdir $log_dir, 0777 ) {
        print "create $log_dir failed:$!\n";
        exit;
    }
}
my $class4_log_file = $log_dir . "/class4.log";
my $open_log        = 1;
if ( !open CLASS4_LOG, ">>$class4_log_file" ) {
    print "open $class4_log_file failed:$!\n";
    $open_log = 0;
}

$ENV{LM_DEBUG} = 1;
local $Log::Minimal::AUTODUMP  = 1;
local $Log::Minimal::LOG_LEVEL = $config{script_log}{log_level} || 'DEBUG';
local $Log::Minimal::PRINT     = sub {
    my ( $time, $type, $message, $trace, $raw_message ) = @_;
    print "$time [$type] $trace $message\n";
    print CLASS4_LOG "$time [$type] $trace $message\n" if ($open_log);
};

infof("start script : $start_cmd");
debugf("Log dir: $log_dir");
my $db_name     = $config{db}{dbname};
my $db_host     = $config{db}{hostaddr};
my $db_port     = $config{db}{port};
my $db_username = $config{db}{user};
my $db_password = $config{db}{password};
# $db_username = 'postgres';
# $db_name = 'class4_pr';
# $db_port = '5432';
# $db_host = '127.0.0.1';
my $dbh = DBI->connect( "dbi:Pg:dbname=$db_name; host=$db_host; port=$db_port",
    $db_username, $db_password, { AutoCommit => 1, pg_server_prepare => 1 } );

if ( !$dbh ) {
    critf($DBI::errstr);
    &do_exit();
}

# e®°a?•e„s??¬cs„a?ˆa§‹?—¶e—?
my $sql =
"INSERT INTO scheduler_log (script_name, start_time) VALUES ('FTP CDR', current_timestamp(0))";
my $sth = $dbh->prepare($sql);
$sth->execute();
my $scheduler_log_id =
  $dbh->last_insert_id( undef, undef, "scheduler_log", "id" );

my $ftp_timeout = $config{script_ftp_cdr}{ftp_timeout};
my $ftp_debug   = 1;
my $start_time;
my $end_time;
my $cdr_split = ",";
my $cdr_dir   = $log_dir . "/ftp_cdr";

debugf("CDR dir: $cdr_dir");
if ( !-d $cdr_dir ) {
    if ( !mkdir $cdr_dir, 0777 ) {
        critf("create $cdr_dir failed:$!");
        &do_exit();
    }
}
my $run_pid_file = $log_dir . "/dnl_ftp_cdr.pid";
my $dt           = DateTime->today();
my $dt_now       = DateTime->now();
my $ti           = gmtime;

my $is_read_conf       = $config{script_ftp_cdr}{is_read_conf};
my $ftp_conf_cdr_field = $config{script_ftp_cdr}{cdr_head};
my $ftp_conf_cdr_alias = $config{script_ftp_cdr}{cdr_alias};
my $ftp_conf_is_head   = $config{script_ftp_cdr}{is_alias};

&start_log();

if ( !chdir "$cdr_dir" ) {
    critf("cannot cd to $cdr_dir:$!");
    my $ftp_log_id = $dbh->selectrow_hashref(
        "SELECT nextval('ftp_cdr_log_id_seq'::regclass) as no")->{no};
    &ftp_log( undef, undef, undef, 1, undef, undef, $ftp_log_id, undef );
    &do_exit();
}

my $sql = "SELECT * from ftp_conf";

if ( $opts{n} ) {
    $sql .= " where id = ${opts{n}}";
}

my $mail_hr = $dbh->selectrow_hashref("SELECT * from system_parameter");

my %mail_template_data;
$mail_template_data{from}      = $mail_hr->{fromemail};
$mail_template_data{fake_from} = $mail_hr->{emailname};
$mail_template_data{server}    = $mail_hr->{smtphost};
$mail_template_data{port}      = $mail_hr->{smtpport};
$mail_template_data{username}  = $mail_hr->{emailusername};
$mail_template_data{password}  = $mail_hr->{emailpassword};
$mail_template_data{auth}      = ( $mail_hr->{loginemail} eq "true" ) ? 1 : 0;

#$mail_template_data{ tls } = $config{ sendmail_tls_require };
$mail_template_data{tls} = $mail_hr->{smtp_secure};
$mail_template_data{ssl} = ( $mail_hr->{smtp_secure} == 2 ) ? 1 : 0;
$mail_template_data{to}  = $mail_hr->{ftp_email};

my $ftp_info = $dbh->selectall_hashref( $sql, "id" );

for my $k1 ( keys %{$ftp_info} ) {

    my $ftp_alias      = $ftp_info->{$k1}->{alias};
    my $ftp_host       = $ftp_info->{$k1}->{server_ip};
    my $ftp_port       = $ftp_info->{$k1}->{server_port};
    my $ftp_user       = $ftp_info->{$k1}->{username};
    my $ftp_pw         = $ftp_info->{$k1}->{password};
    my $cdr_field      = $ftp_info->{$k1}->{fields};
    my $cdr_alias      = $ftp_info->{$k1}->{headers};
    my $frequency      = $ftp_info->{$k1}->{frequency};
    my $is_head        = $ftp_info->{$k1}->{contain_headers};
    my $file_type      = $ftp_info->{$k1}->{file_type};
    my $conditions     = $ftp_info->{$k1}->{conditions};
    my $time_hour      = $ftp_info->{$k1}->{time};
    my $ftp_conf_id    = $ftp_info->{$k1}->{id};
    my $server_dir     = $ftp_info->{$k1}->{server_dir};
    my $max_lines      = $ftp_info->{$k1}->{max_lines};
    my $actived        = $ftp_info->{$k1}->{active};
    my $every_hours    = $ftp_info->{$k1}->{every_hours};
    my $file_breakdown = $ftp_info->{$k1}->{file_breakdown};
    my $every_minutes  = $ftp_info->{$k1}->{every_minutes};
    if ($is_read_conf) {
        $cdr_field  = $ftp_conf_cdr_field;
        $cdr_alias  = $ftp_conf_cdr_alias;
        $is_head    = $ftp_conf_is_head;
        $conditions = '';
    }

    if ( $opts{a} ) {
        if ( !$actived ) {
            next;
        }
        &start_pid($run_pid_file) if ( $start_pid == 0 );

        my ( $start_time, $end_time, $cdr_start_time, $cdr_end_time );

        my @timelist = ();

        debugf("auto ftp cdr, frequency: $frequency");

        if ( $frequency == 0 ) {
            debugf("$ftp_alias Nothing to do!");
            next;
        }
        elsif ( $frequency == 1 ) {
            debugf("Hour: $time_hour");
            debugf( "Current Hour:%d",   $ti->hour );
            debugf( "Current Minute:%d", $ti->minute );
            if ( $ti->minute != 0 ) {
                debugf("$ftp_alias Nothing to do! ");
                next;
            }
            if ( $ti->hour != $time_hour ) {
                debugf("$ftp_alias Nothing to do! ");
                next;
            }

            if ( $file_breakdown == 1 ) {
                my $duration = DateTime::Duration->new( days => 1 );
                my $dt2 = $dt - $duration;
                $cdr_start_time = $dt2->strftime("%Y-%m-%d 00:00:00");
                $cdr_end_time   = $dt2->strftime("%Y-%m-%d 23:59:59");
                my $start_time_dt = $dt2->clone();
                my $end_time_dt   = $dt2->clone();
                $start_time_dt->set_hour(0);
                $start_time_dt->set_minute(0);
                $start_time_dt->set_second(0);
                $end_time_dt->set_hour(23);
                $end_time_dt->set_minute(59);
                $end_time_dt->set_second(59);

                while ( $start_time_dt <= $end_time_dt ) {
                    debugf( $start_time_dt->ymd );
                    $start_time = $start_time_dt->strftime("%Y-%m-%d %H:00:00");
                    $end_time   = $start_time_dt->strftime("%Y-%m-%d %H:59:59");
                    push @timelist, [ $start_time, $end_time ];
                    $start_time_dt->add( hours => 1 );
                }

            }
            else {
                my $duration = DateTime::Duration->new( days => 1 );
                my $dt2 = $dt - $duration;
                $start_time     = $dt2->strftime("%Y-%m-%d 00:00:00");
                $end_time       = $dt2->strftime("%Y-%m-%d 23:59:59");
                $cdr_start_time = $start_time;
                $cdr_end_time   = $end_time;
                push @timelist, [ $start_time, $end_time ];
            }

        }
        elsif ( $frequency == 2 ) {
            debugf("Hour: $time_hour");
            debugf( "Current Hour:%d",   $ti->hour );
            debugf( "Current Minute:%d", $ti->minute );
            if ( $ti->minute != 0 ) {
                debugf("$ftp_alias Nothing to do!");
                next;
            }
            if ( $ti->hour != $time_hour ) {
                next;
            }
            my $day_of_week = $dt->day_of_week();
            unless ( $day_of_week == 2 ) {
                debugf(
"$ftp_alias Nothing to do at the week day of ${day_of_week}!"
                );
                next;
            }

            if ( $file_breakdown == 0 ) {
                my $duration = DateTime::Duration->new( days => 7 );
                my $dt2 = $dt - $duration;
                $duration = DateTime::Duration->new( days => 1 );
                my $dt3 = $dt - $duration;
                $cdr_start_time = $dt2->strftime("%Y-%m-%d 00:00:00");
                $cdr_end_time   = $dt3->strftime("%Y-%m-%d 23:59:59");

                $start_time = $dt2->strftime("%Y-%m-%d 00:00:00");
                $end_time   = $dt3->strftime("%Y-%m-%d 23:59:59");
                push @timelist, [ $start_time, $end_time ];

            }
            elsif ( $file_breakdown == 1 ) {
                my $duration = DateTime::Duration->new( days => 7 );
                my $dt2 = $dt - $duration;
                $duration = DateTime::Duration->new( days => 1 );
                my $dt3 = $dt - $duration;
                $cdr_start_time = $dt2->strftime("%Y-%m-%d 00:00:00");
                $cdr_end_time   = $dt3->strftime("%Y-%m-%d 23:59:59");

                while ( $dt2 <= $dt3 ) {
                    debugf( $dt2->ymd );
                    $start_time = $dt2->strftime("%Y-%m-%d %H:00:00");
                    $end_time   = $dt2->strftime("%Y-%m-%d %H:59:59");
                    push @timelist, [ $start_time, $end_time ];
                    $dt2->add( hours => 1 );
                }
            }
            else {
                my $duration = DateTime::Duration->new( days => 7 );
                my $dt2 = $dt - $duration;
                $duration = DateTime::Duration->new( days => 1 );
                my $dt3 = $dt - $duration;
                $cdr_start_time = $dt2->strftime("%Y-%m-%d 00:00:00");
                $cdr_end_time   = $dt3->strftime("%Y-%m-%d 23:59:59");

                while ( $dt2 <= $dt3 ) {
                    debugf( $dt2->ymd );
                    $start_time = $dt2->strftime("%Y-%m-%d 00:00:00");
                    $end_time   = $dt2->strftime("%Y-%m-%d 23:59:59");
                    push @timelist, [ $start_time, $end_time ];
                    $dt2->add( days => 1 );
                }
            }

        }
        elsif ( $frequency == 3 ) {

            #debugf("Hour: $time_hour");
            debugf( "Current Hour:%d",   $ti->hour );
            debugf( "Current Minute:%d", $ti->minute );
			
            if ( $ti->minute != 0 ) {
                debugf("$ftp_alias Nothing to do!");
                next;
            }
            my $duration = DateTime::Duration->new( hours => 1 );
            $dt = $dt_now;
            my $dt2 = $dt - $duration;
            if ( $dt2->hour() % $every_hours != 0 ) {
                next;
            }
            
            if ( $file_breakdown == 1 ) {
                $duration = DateTime::Duration->new( hours => $every_hours );
                my $dt3 = $dt - $duration;
                $cdr_start_time = $dt3->strftime("%Y-%m-%d %H:00:00");
                $cdr_end_time   = $dt2->strftime("%Y-%m-%d %H:59:59");
                while ( $dt3 <= $dt2 ) {
                    debugf( $dt3->ymd );
                    $start_time = $dt3->strftime("%Y-%m-%d %H:00:00");
                    $end_time   = $dt3->strftime("%Y-%m-%d %H:59:59");
                    push @timelist, [ $start_time, $end_time ];
                    $dt3->add( hours => 1 );
                }
            }
            else {
                $duration = DateTime::Duration->new( hours => $every_hours );
                my $dt3 = $dt - $duration;
                $cdr_start_time = $dt3->strftime("%Y-%m-%d %H:00:00");
                $cdr_end_time   = $dt2->strftime("%Y-%m-%d %H:59:59");
                $start_time     = $dt3->strftime("%Y-%m-%d %H:00:00");
                $end_time       = $dt2->strftime("%Y-%m-%d %H:59:59");
                push @timelist, [ $start_time, $end_time ];
            }

        }
        elsif ( $frequency == 4 ) {
            debugf( "Current minute:%d", $ti->minute );

            my $duration = DateTime::Duration->new( minutes => 15 );
            $dt = $dt_now;
            my $dt2 = $dt - $duration;
            if ( $dt2->minute() % $every_minutes != 0 ) {
                next;
            }
            my $duration = DateTime::Duration->new( minutes => $every_minutes );
            debugf($duration);
            my $dt3 = $dt - $duration;
            $cdr_start_time = $dt3->strftime("%Y-%m-%d %H:%M:00");
            $cdr_end_time   = $dt_now->strftime("%Y-%m-%d %H:%M:00");
            $start_time     = $dt3->strftime("%Y-%m-%d %H:%M:00");
            $end_time       = $dt_now->strftime("%Y-%m-%d %H:%M:00");
            push @timelist, [ $start_time, $end_time ];

        }

        debugf(@timelist);

        my $ftp_start_time = strftime( "%Y-%m-%d %H:%M:%S", gmtime );

        my $ftp_log_id = $dbh->selectrow_hashref(
            "SELECT nextval('ftp_cdr_log_id_seq'::regclass) as no")->{no};
		debugf("insert ftp log   ");
		
		debugf(Dumper([            $ftp_alias,    $ftp_start_time, undef,        2,
            $ftp_host,     $ftp_port,       $ftp_conf_id, $cdr_start_time,
            $cdr_end_time, $ftp_log_id,     $shell_pid]));
			
			
        &ftp_log(
            $ftp_alias,    $ftp_start_time, undef,        2,
            $ftp_host,     $ftp_port,       $ftp_conf_id, $cdr_start_time,
            $cdr_end_time, $ftp_log_id,     $shell_pid
        );

        my $filename_prefix = "${cdr_dir}/${ftp_alias}";
		## TEST
        my $create_res = &create_cdr(
            \@timelist,  $filename_prefix, undef,       $cdr_field,
            $cdr_alias,  $is_head,         $conditions, $file_type,
            $ftp_host,   $ftp_port,        $ftp_user,   $ftp_pw,
            $ftp_log_id, $server_dir,      $max_lines
        );
        if ( $create_res == 0 ) {
            ftp_log_change_status( 4, $ftp_log_id );
        }
        elsif ( $create_res == -1 ) {
            $mail_template_data{subject} =
"The FTP Job [${ftp_alias}] is generated failed between $cdr_start_time and $cdr_end_time !";
            $mail_template_data{content} =
"The FTP Job [${ftp_alias}] is generated failed between $cdr_start_time and $cdr_end_time !";
            &send_mail(%mail_template_data);
        }

        my $ftp_end_time = strftime( "%Y-%m-%d %H:%M:%S", gmtime );
        &ftp_log_completed( $ftp_end_time, $ftp_log_id );
		#### TEST
=pod=
        debugf("push  redis ");
        lpush_redis(
            \@timelist,  $filename_prefix, undef,
            $cdr_field,  $cdr_alias,       $is_head,
            $conditions, $file_type,       $ftp_host,
            $ftp_port,   $ftp_user,        $ftp_pw,
            $ftp_log_id, $server_dir,      $max_lines,
            $ftp_alias,  $cdr_start_time,  $cdr_end_time
        );
        debugf("bdc");
=cut=
    }
    else {

        my $start_time     = $opts{s};
        my $end_time       = $opts{e};
        my $file_breakdown = $opts{p};

        my $parser =
          DateTime::Format::Strptime->new( pattern => '%Y-%m-%d %H:%M:%S' );
        my $start_time_dt = $parser->parse_datetime($start_time);
        my $end_time_dt   = $parser->parse_datetime($end_time);

        my $cdr_start_time = $start_time_dt->strftime("%Y-%m-%d %H:%M:%S");
        my $cdr_end_time   = $end_time_dt->strftime("%Y-%m-%d %H:%M:%S");

        my @timelist = ();

        if ( $frequency == 0 ) {
            debugf("$ftp_alias Nothing to do!");
            next;
        }
        else {
            if ( $file_breakdown == 0 ) {
                debugf( $start_time_dt->ymd );
                $start_time = $start_time_dt->strftime("%Y-%m-%d %H:%M:%S");
                $end_time   = $end_time_dt->strftime("%Y-%m-%d %H:%M:%S");
                push @timelist, [ $start_time, $end_time ];
            }
            elsif ( $file_breakdown == 1 ) {
                while ( $start_time_dt <= $end_time_dt ) {
                    debugf( $start_time_dt->ymd );
                    $start_time = $start_time_dt->strftime("%Y-%m-%d %H:00:00");
                    $end_time   = $start_time_dt->strftime("%Y-%m-%d %H:59:59");
                    push @timelist, [ $start_time, $end_time ];
                    $start_time_dt->add( hours => 1 );
                }
            }
            else {
                while ( $start_time_dt <= $end_time_dt ) {
                    debugf( $start_time_dt->ymd );
                    $start_time = $start_time_dt->strftime("%Y-%m-%d 00:00:00");
                    $end_time   = $start_time_dt->strftime("%Y-%m-%d 23:59:59");
                    push @timelist, [ $start_time, $end_time ];
                    $start_time_dt->add( days => 1 );
                }
            }
        }

        my $ftp_start_time = strftime( "%Y-%m-%d %H:%M:%S", gmtime );

        my $ftp_log_id = $dbh->selectrow_hashref(
            "SELECT nextval('ftp_cdr_log_id_seq'::regclass) as no")->{no};
        &ftp_log(
            $ftp_alias,    $ftp_start_time, undef,        3,
            $ftp_host,     $ftp_port,       $ftp_conf_id, $cdr_start_time,
            $cdr_end_time, $ftp_log_id,     $shell_pid
        );
		
        my $filename_prefix = "${cdr_dir}/${ftp_alias}";

        my $create_res = &create_cdr(
            \@timelist,  $filename_prefix, undef,       $cdr_field,
            $cdr_alias,  $is_head,         $conditions, $file_type,
            $ftp_host,   $ftp_port,        $ftp_user,   $ftp_pw,
            $ftp_log_id, $server_dir,      $max_lines
        );
        if ( $create_res == 0 ) {
            ftp_log_change_status( 4, $ftp_log_id );
        }
        elsif ( $create_res == -1 ) {
            $mail_template_data{subject} =
"The FTP Job [${ftp_alias}] is generated failed between $cdr_start_time and $cdr_end_time !";
            $mail_template_data{content} =
"The FTP Job [${ftp_alias}] is generated failed between $cdr_start_time and $cdr_end_time !";
            &send_mail(%mail_template_data);
        }

        my $ftp_end_time = strftime( "%Y-%m-%d %H:%M:%S", gmtime );
        &ftp_log_completed( $ftp_end_time, $ftp_log_id );

        #        lpush_redis(
        #            \@timelist,  $filename_prefix, undef,
        #           $cdr_field,  $cdr_alias,       $is_head,
        #            $conditions, $file_type,       $ftp_host,
        #           $ftp_port,   $ftp_user,        $ftp_pw,
        #            $ftp_log_id, $server_dir,      $max_lines,
        #           $ftp_alias,  $cdr_start_time,  $cdr_end_time
        #        );
    }
}
# e®°a?•e„s??¬cs„c»“??Y?—¶e—?
my $sql =
  "update scheduler_log set end_time = current_timestamp(0) where id =?";
my $sth = $dbh->prepare($sql);
$sth->execute($scheduler_log_id);
&do_exit();

sub init() {
    my $opt_string = 'ahc:s:e:n:p:';
    getopts( "$opt_string", \%opts ) or usage();
    usage() if $opts{h};
}

sub usage() {
    print
"perl class4_ftp_cdr.pl -c class4.conf -a or -s start time -e end time -n alias_id -p [0:As one big file, 1:As hourly file, 2:As daily file]\n";
    exit;
}

sub mod_data {
    my ( $data_sql, $data_value ) = @_;
    my @value_array;
    if ( ref($data_value) eq "ARRAY" ) {
        @value_array = @$data_value;
    }
    else {
        @value_array = split( /,/, $data_value, -1 );
    }

    my $sth = $dbh->prepare($data_sql);

    $sth->execute(@value_array);
    if ( $sth->state ) {
        critf(
            "update database error sql:%s,data:%s,error:%s",
            $data_sql, join( ",", @value_array ),
            $sth->errstr
        );
        return 1;
    }
    return 0;
}

sub start_log() {
    my @script_name = split( /\//, $0 );

    &mod_data(
"update scheduler set last_run = current_timestamp(0) where script_name=?",
        "@script_name[$#script_name]"
    );

    $start_log = 1;
}

sub start_pid() {
    return 0;
    my ($pid_file) = @_;

    if ( -e $pid_file ) {
        my $pid = `head -1 $pid_file`;
        debugf("last pid : $pid");
        if ( $pid !~ /^\s*$/ && -e "/proc/$pid" ) {
            debugf("pid $pid alreay running");
            &do_exit();
        }
    }
    if ( !open PID_FILE, ">$pid_file" ) {
        critf("open pid file error:$!");
        &do_exit();
    }

    print PID_FILE "$$";
    close PID_FILE;

    debugf("new pid : $$");

    $start_pid = 1;

    return 0;
}

sub end_pid() {
    my ($pid_file) = @_;

    debugf("process done");

    if ( !open PID_FILE, ">$pid_file" ) {
        critf("close pid file error:$!");
    }
    print PID_FILE "";
    close PID_FILE;

    return 0;
}

sub do_exit() {
    &start_log() if ($start_log);
    $dbh->disconnect if ($dbh);
    close CLASS4_LOG;
    &end_pid($run_pid_file) if ($start_pid);

    exit;
}

=pod=
sub lpush_redis() {
    my (
        $timelist_ref,   $out_file_prefix, $server_ip,  $cdr_column,
        $cdr_name,       $has_head,        $conditions, $file_type,
        $ftp_host,       $ftp_port,        $ftp_user,   $ftp_pw,
        $ftp_log_id,     $server_dir,      $max_lines,  $ftp_alias,
        $cdr_start_time, $cdr_end_time
    ) = @_;
    
    my $ftp = Net::FTP->new(
        $ftp_host,
        Port    => $ftp_port,
        Timeout => $ftp_timeout,
        Debug   => 1
    );

    my $message = '';
    my $upload_filename =
            $out_file_prefix . "_"
          . $cdr_start_time . "_"
          . $cdr_end_time . ".csv";
        $upload_filename =~ s/\s|:/_/g;
    if ( !defined $ftp ) {
        critf("Cannot connect to $ftp_host:$@");
        ftp_log_change_status( -1, $ftp_log_id );
        $message .= "\n Cannot connect to $ftp_host";
        my $ftp_log_detail_id = $dbh->selectrow_hashref(
            "SELECT nextval('ftp_cdr_log_detail_id_seq'::regclass) as no")
          ->{no};
        &ftp_log_detail( $ftp_log_id, strftime( "%Y-%m-%d %H:%M:%S", gmtime ),
            '', $ftp_host, $server_dir, $message, $upload_filename,
            $ftp_log_detail_id );

        #unlink $upload_filename;
        &ftp_server_log( "CONNECT $ftp_host", "Fail" );
        return -1;
    }

    $message .= "\n  Connected to $ftp_host";
    &ftp_server_log( "CONNECT $ftp_host", "SUCCESS" );

    if ( !$ftp->login( $ftp_user, $ftp_pw ) ) {
        critf( "Cannot login:%s", $ftp->message );
        ftp_log_change_status( -2, $ftp_log_id );
        $message .= "\n" . $ftp->message;
        my $ftp_log_detail_id = $dbh->selectrow_hashref(
            "SELECT nextval('ftp_cdr_log_detail_id_seq'::regclass) as no")
          ->{no};
        &ftp_log_detail( $ftp_log_id, strftime( "%Y-%m-%d %H:%M:%S", gmtime ),
            '', $ftp_host, $server_dir, $message, $upload_filename,
            $ftp_log_detail_id );
        &ftp_server_log( "AUTH $ftp_user", "Fail" );

        #unlink $upload_filename;
        return -2;
    }
    
    my $data = [
        {
            'timelist_ref'    => $timelist_ref,
            'out_file_prefix' => $out_file_prefix,
            'server_ip'       => $server_ip,
            'cdr_column'      => $cdr_column,
            'cdr_name'        => $cdr_name,
            'has_head'        => $has_head,
            'conditions'      => $conditions,
            'file_type'       => $file_type,
            'ftp_host'        => $ftp_host,
            'ftp_port'        => $ftp_port,
            'ftp_user'        => $ftp_user,
            'ftp_pw'          => $ftp_pw,
            'ftp_log_id'      => $ftp_log_id,
            'server_dir'      => $server_dir,
            'max_lines'       => $max_lines,
            'ftp_alias'       => $ftp_alias,
            'cdr_start_time'  => $cdr_start_time,
            'cdr_end_time'    => $cdr_end_time,
        }
    ];
    my $json_out   = encode_json($data);
    #my $redis_ip   = $config{redis}{ip};
    #my $redis_port = $config{redis}{port};
#
    #my $redis = Redis::hiredis->new();
    #$redis->connect( $redis_ip, $redis_port );

    debugf($json_out);
    my $client_name = $config{client}{name};
    #my $redis_key = "cdr_ftp[" .$client_name . "]";
    #$redis->lpush( $redis_key, $json_out );

}
=cut=

sub create_cdr() {
    my (
        $timelist_ref, $out_file_prefix, $server_ip,  $cdr_column,
        $cdr_name,     $has_head,        $conditions, $file_type,
        $ftp_host,     $ftp_port,        $ftp_user,   $ftp_pw,
        $ftp_log_id,   $server_dir,      $max_lines
    ) = @_;

    my $flag = 0;

    #print "cdr file $out_file\n";
    #a?¤?–­conditionsa­—?®µ???a?¦a??c©?
    my $condition = '';
    if ( $conditions ne '' ) {
        $condition = "and " . $conditions;    #aS a?S???a»¶
    }
    my $strp =  DateTime::Format::Strptime->new(
        pattern   => '%F %T'
    );


    for my $time_element (@$timelist_ref) {
        my ( $cdr_start_date, $cdr_end_date ) = @$time_element;

my $earliest_date_row = $dbh->selectrow_hashref("select table_name from information_schema.tables where table_schema='public' and table_name similar to 'client_cdr[12]%' order by table_name limit 1");
my $earliest_date = $earliest_date_row->{table_name};
$earliest_date =~ s/^client_cdr//;
my $latest_date_row = $dbh->selectrow_hashref("select table_name from information_schema.tables where table_schema='public' and table_name similar to 'client_cdr[12]%' order by table_name desc limit 1");
my $latest_date = $latest_date_row->{table_name};
$latest_date =~ s/^client_cdr//;
        $earliest_date =~ s/^(....)(..)(..)$/\1-\2-\3 00:00:00/;
        $latest_date =~ s/^(....)(..)(..)$/\1-\2-\3 23:59:59/;
        $cdr_start_date = $earliest_date if($earliest_date > $cdr_start_date);
        $cdr_end_date = $latest_date if($latest_date > $cdr_end_date);
        my $invoice_start_time_bak = $cdr_start_date;
        my $invoice_end_time_bak = $cdr_end_date;





    my $dt1 = $strp->parse_datetime($cdr_start_date);
    my $dt2 = $strp->parse_datetime($cdr_end_date);
        print( $condition, "--------------\n" );
	my $cdr_column1 = $cdr_column;
	$cdr_column1 =~ s#client_cdr#client_cdr@{[$dt1->ymd('')]}#g;
        my $sql =
#"copy (select $cdr_column from client_cdr where time between ? and ? ${condition} order by time) to stdout delimiter as '$cdr_split' csv header";
"copy (select $cdr_column1 from client_cdr@{[$dt1->ymd('')]} where true ${condition} ";
for($dt1->add(days => 1); $dt1 <= $dt2; $dt1->add(days => 1)) { 
	$cdr_column1 = $cdr_column;
	$cdr_column1 =~ s#client_cdr#client_cdr@{[$dt1->ymd('')]}#g;
"union select $cdr_column1 from client_cdr@{[$dt1->ymd('')]} where true ${condition} ";
}
$sql .= "order by time) to stdout delimiter as '$cdr_split' csv header";
    my $dt1 = $strp->parse_datetime($cdr_start_date);
        if ($server_ip) {
	$cdr_column1 = $cdr_column;
	$cdr_column1 =~ s#client_cdr#client_cdr@{[$dt1->ymd('')]}#g;
            $sql =
#"copy (select $cdr_column from client_cdr where time between ? and ? and origination_destination_host_name = '$server_ip' ${condition} order by time) to stdout delimiter as '$cdr_split' csv header";
"copy (select $cdr_column1 from client_cdr@{[$dt1->ymd('')]} where origination_destination_host_name = '$server_ip' ${condition} ";
for($dt1->add(days => 1); $dt1 <= $dt2; $dt1->add(days => 1)) { 
	$cdr_column1 = $cdr_column;
	$cdr_column1 =~ s#client_cdr#client_cdr@{[$dt1->ymd('')]}#g;
"union select $cdr_column1 from client_cdr@{[$dt1->ymd('')]} where origination_destination_host_name = '$server_ip' ${condition} ";
}
$sql .= " order by time) to stdout delimiter as '$cdr_split' csv header";
        }
print $sql, "\n";
        my @time_cdr = ( $cdr_start_date, $cdr_end_date );
        #if ( !$dbh->do( $sql, undef, @time_cdr ) ) {
        if ( !$dbh->do( $sql ) ) {
            critf( "copy database data error : %s", $dbh->errstr );
            return -2;
        }
        my @data;
        my $x = 0;
        my $start_time = strftime "%s", gmtime;
        while ( $dbh->pg_getcopydata( @data[ $x++ ] ) >= 0 ) {

        }
        my $end_time  = strftime "%s", gmtime;
        my $copy_time = $end_time - $start_time;
        my $total_cdr = $x - 2;
        debugf("total cdr $total_cdr, copy time $copy_time(s)");
        if ( $total_cdr == 0 ) {

            #return $total_cdr;
            #next;
        }

        my $report_hf = {};
        delete $report_hf->{""};
        my $report_total_hf = {};
        delete $report_total_hf->{""};

        my $filenum = 0;
        my $out_file =
            $out_file_prefix . "_"
          . $cdr_start_date . "_"
          . $cdr_end_date . ".csv";
        $out_file =~ s/\s|:/_/g;
		$out_file =~ s/\/+/\//g;
		debugf("Out file: $out_file");
		print `pwd`;
        if ( !open OUT_RES_FILE, ">$out_file" ) {
            critf("open $out_file failed:$!");
            return -1;
        }

        #print "$cdr_name\n";
        if ( $has_head == 1 ) {
            print OUT_RES_FILE "$cdr_name\n";
        }

        my $upload_res;

        if ( !$max_lines ) {
            $max_lines = 10000;
        }

        for ( my $i = 1 ; $i < $x - 1 ; $i++ ) {
            if ( $i % $max_lines == 0 ) {
                close OUT_RES_FILE;
                my $ftp_start_time = strftime( "%Y-%m-%d %H:%M:%S", gmtime );
                $upload_res = &upload_file(
                    $out_file, $file_type, $ftp_host,   $ftp_port,
                    $ftp_user, $ftp_pw,    $ftp_log_id, $server_dir
                );
                my $ftp_end_time = strftime( "%Y-%m-%d %H:%M:%S", gmtime );

                if ( $upload_res != 0 ) {
                    $flag = -1;
                }

                $filenum = $filenum + 1;
                $out_file =
                    $out_file_prefix . "_"
                  . $cdr_start_date . "_"
                  . $cdr_end_date . "_"
                  . $filenum . ".csv";
                $out_file =~ s/\s|:/_/g;
                if ( !open OUT_RES_FILE, ">$out_file" ) {
                    critf("open $out_file failed:$!");
                    return -1;
                }
                if ( $has_head == 1 ) {
                    print OUT_RES_FILE "$cdr_name\n";
                }
            }
            print OUT_RES_FILE "$data[$i]";
        }

        close OUT_RES_FILE;
        $upload_res = &upload_file(
            $out_file, $file_type, $ftp_host,   $ftp_port,
            $ftp_user, $ftp_pw,    $ftp_log_id, $server_dir
        );
        if ( $upload_res != 0 ) {
            $flag = -1;
        }
    }
    return $flag;
}

sub upload_file() {
    my ( $upload_filename, $zip_type, $ftp_host, $ftp_port, $ftp_user, $ftp_pw,
        $ftp_log_id, $server_dir )
      = @_;
	  
	$ftp_host =~ s/^ftp:\/\///; ## XXX needs to be changed in the config table
    my $ftp = Net::FTP->new(
        $ftp_host,
        Port    => $ftp_port,
        Timeout => $ftp_timeout,
        Debug   => 1
    );

    my $message = '';

    if ( !defined $ftp ) {
        critf("Cannot connect to $ftp_host:$@");
        ftp_log_change_status( -1, $ftp_log_id );
        $message .= "\n Cannot connect to $ftp_host";
        my $ftp_log_detail_id = $dbh->selectrow_hashref(
            "SELECT nextval('ftp_cdr_log_detail_id_seq'::regclass) as no")
          ->{no};
        &ftp_log_detail( $ftp_log_id, strftime( "%Y-%m-%d %H:%M:%S", gmtime ),
            '', $ftp_host, $server_dir, $message, $upload_filename,
            $ftp_log_detail_id );

        #unlink $upload_filename;
        &ftp_server_log( "CONNECT $ftp_host", "Fail" );
        return -1;
    }

    $message .= "\n  Connected to $ftp_host";
    &ftp_server_log( "CONNECT $ftp_host", "SUCCESS" );

    if ( !$ftp->login( $ftp_user, $ftp_pw ) ) {
        critf( "Cannot login:%s", $ftp->message );
        ftp_log_change_status( -2, $ftp_log_id );
        $message .= "\n" . $ftp->message;
        my $ftp_log_detail_id = $dbh->selectrow_hashref(
            "SELECT nextval('ftp_cdr_log_detail_id_seq'::regclass) as no")
          ->{no};
        &ftp_log_detail( $ftp_log_id, strftime( "%Y-%m-%d %H:%M:%S", gmtime ),
            '', $ftp_host, $server_dir, $message, $upload_filename,
            $ftp_log_detail_id );
        &ftp_server_log( "AUTH $ftp_user", "Fail" );

        #unlink $upload_filename;
        return -2;
    }
    $message .= "\n" . $ftp->message;
    &ftp_server_log( "AUTH $ftp_user", "SUCCESS" );

    my $zip_file = $upload_filename;
    my $zip_cmd;
    my $basename = basename($upload_filename);
    my $dirname  = dirname($upload_filename);
    print( $dirname,  "--------\n" );
    print( $basename, "-------\n" );

    ###zip_file
    if ( $zip_type == 1 ) {
        ###gz
        $zip_file = $upload_filename . ".gz";
        $zip_cmd  = "cat $upload_filename | gzip > $zip_file";
    }
    elsif ( $zip_type == 2 ) {
        ###tar.gz
        $zip_file = $upload_filename . ".tar.gz";
        $zip_cmd  = "tar -czf $zip_file -C $dirname $basename";
    }
    elsif ( $zip_type == 3 ) {
        ###tar.bz2
        $zip_file = $upload_filename . ".tar.bz2";
        $zip_cmd  = "tar -cjf $zip_file -C $dirname $basename";
    }

    debugf("$zip_cmd");
    system($zip_cmd );
    unlink $upload_filename;

    if ($server_dir) {

        #$server_dir =~ s/^\/+|\/+$//g ;
        $server_dir =~ s/^\/+|\/$//g;
        debugf($server_dir);
        $ftp->mkdir( $server_dir, 1 );
        &ftp_server_log( "MKD $server_dir", $ftp->message );
        $message .= "\n" . $ftp->message;
        $ftp->cwd($server_dir);
        &ftp_server_log( "CWD $server_dir", $ftp->message );
        $message .= "\n" . $ftp->message;
    }

    ###upload file
    $ftp->binary();
    my $put_file = $ftp->put($zip_file);
    $message .= "\n" . $ftp->message;
    if ( !$put_file ) {
        critf( "Cannot Upload:%s", $ftp->message );
        $message .= "\n" . $ftp->message;
        $ftp->quit();
        ftp_log_change_status( -3, $ftp_log_id );
        my $ftp_log_detail_id = $dbh->selectrow_hashref(
            "SELECT nextval('ftp_cdr_log_detail_id_seq'::regclass) as no")
          ->{no};
        &ftp_log_detail( $ftp_log_id, strftime( "%Y-%m-%d %H:%M:%S", gmtime ),
            '', $ftp_host, $server_dir, $message, $upload_filename,
            $ftp_log_detail_id );
        &ftp_server_log( "PUT $zip_file", "FAIL" );

        #unlink $upload_filename,$zip_file;
        unlink $zip_file;
        return -3;
    }
    &ftp_server_log( "PUT $put_file", "SUCCESS" );
    debugf("uploaded : $put_file");
    my $basename = basename($put_file);

    ###delete old file
    #unlink $upload_filename,$zip_file;
    unlink $zip_file;

    $ftp->quit();

    my $ftp_log_detail_id = $dbh->selectrow_hashref(
        "SELECT nextval('ftp_cdr_log_detail_id_seq'::regclass) as no")->{no};
    &ftp_log_detail( $ftp_log_id, strftime( "%Y-%m-%d %H:%M:%S", gmtime ),
        $basename, $ftp_host, $server_dir, $message, $upload_filename,
        $ftp_log_detail_id );

    return 0;
}

sub ftp_log() {

    &mod_data(
        "INSERT INTO ftp_cdr_log(
			alias,ftp_start_time, ftp_end_time, 
            status, ftp_ip, ftp_dir, ftp_conf_id, cdr_start_time, cdr_end_time, id, pid)
    VALUES (?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?)", \@_
    );

}

sub ftp_log_change_status() {
    &mod_data( "UPDATE ftp_cdr_log set status = ? WHERE  id = ?", \@_ );
}

sub ftp_log_change_detail() {
    &mod_data( "UPDATE ftp_cdr_log set detail = detail || ? WHERE  id = ?",
        \@_ );
}

sub ftp_log_completed() {
    &mod_data( "UPDATE ftp_cdr_log set ftp_end_time = ? WHERE  id = ?", \@_ );
}

sub ftp_log_detail() {
    &mod_data(
        "INSERT INTO ftp_cdr_log_detail(
			ftp_cdr_log_id,create_time, file_name, 
            ftp_ip, ftp_dir, detail,local_file_path,id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)", \@_
    );
}

sub ftp_server_log() {

    &mod_data( "INSERT INTO ftp_server_log(cmd, response) values (?, ?)", \@_ );
}

sub send_mail() {
    my (%mail_data)       = @_;
    my $mail_from         = $mail_data{from};
    my $mail_fake_from    = $mail_data{fake_from};
    my $mail_server       = $mail_data{server};
    my $mail_port         = $mail_data{port};
    my $mail_auth         = $mail_data{auth};
    my $mail_username     = $mail_data{username};
    my $mail_password     = $mail_data{password};
    my $mail_tls_require  = $mail_data{tls};
    my $mail_to           = $mail_data{to};
    my $mail_subject      = $mail_data{subject};
    my $mail_content      = $mail_data{content};
    my $mail_attach_files = $mail_data{files};
    my $mail_ssl          = $mail_data{ssl};
    my $mail_cc           = $mail_data{cc};

    my $attach_data;
    if ($mail_attach_files) {
        for my $k1 (@$mail_attach_files) {
            if ( !open( MAIL_DATA, "<$k1" ) ) {
                critf("open $k1 failed,$!");
                return "open $k1 failed,$!";
            }
            else {
                local $/;

                #my @file_data = <MAIL_DATA>;
                my @mail_attach_files = split( /\//, "$k1" );
                $attach_data->{ @mail_attach_files[$#mail_attach_files] } =
                  <MAIL_DATA>;
                close MAIL_DATA;
            }
        }
    }

    my $email;
    if ($mail_attach_files) {
        my @parts;
        push @parts,
          Email::MIME->create(
            attributes => {
                content_type => "text/html",
                charset      => "utf-8",
                encoding     => "base64",
            },
            body_str => $mail_content,
          );
        for my $k1 ( keys %{$attach_data} ) {
            push @parts,
              Email::MIME->create(
                attributes => {
                    content_type => "application/octet-stream",
                    encoding     => "base64",
                    filename     => "$k1",
                },
                body => $attach_data->{$k1},
              );
        }
        $email = Email::MIME->create(
            header_str => [
                From    => $mail_from,
                To      => $mail_to,
                Subject => $mail_subject,
                Cc      => $mail_cc,
            ],
            parts => [@parts],
        );
    }
    else {
        $email = Email::MIME->create(
            header_str => [
                From    => $mail_from,
                To      => $mail_to,
                Subject => $mail_subject,
                Cc      => $mail_cc,
            ],
            body       => $mail_content,
            attributes => {
                content_type => "text/html",
                charset      => "utf-8",
                encoding     => "base64",
            },
        );
    }

    #print $email->as_string;
    my $send_res;
    if ( $mail_auth == 0 ) {
        try {
            sendmail($email);
        }
        catch {
            $send_res = $_;
        }
    }
    elsif ( $mail_tls_require == 1 ) {
        try {
            sendmail(
                $email,
                {
                    transport => Email::Sender::Transport::SMTP::TLS->new(
                        host     => $mail_server,
                        port     => $mail_port,
                        username => $mail_username,
                        password => $mail_password,
                    ),
                }
            );
        }
        catch {
            $send_res = $_;
        }
    }
    else {
        try {
            sendmail(
                $email,
                {
                    transport => Email::Sender::Transport::SMTP->new(
                        host          => $mail_server,
                        port          => $mail_port,
                        sasl_username => $mail_username,
                        sasl_password => $mail_password,
                        ssl           => $mail_data{ssl},
                    ),
                }
            );
        }
        catch {
            $send_res = $_;
        }
    }

    #print "send result : $send_res\n";
    $send_res =~ s#Trace begun[\s\S]*##g;
    return $send_res;
}