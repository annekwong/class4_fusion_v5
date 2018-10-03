#!/usr/bin/perl

use strict;
use Tie::Handle::CSV;


my $diff_rate = 0;

if( $#ARGV != 8 ){
	print "USAGE: perl cdr-com.pl -sf source_file -df diff_file -o output_file -t cdr_duration_diff -norate/-rate\n";
	exit;
}

my $arg;
my $i = 1;
my $source_filename;
my $diff_filename;
my $res_file;
my $time_diff;
foreach $arg (@ARGV) {
	if( $arg =~ /^-sf/ ) {
		$source_filename = @ARGV[$i];
	}elsif( $arg =~ /^-df/ ) {
		$diff_filename = @ARGV[$i];
	}elsif( $arg =~ /^-o/ ) {
		$res_file = @ARGV[$i];
	}elsif( $arg =~ /^-t/ ) {
		$time_diff = @ARGV[$i];
	}elsif( $arg eq '-norate' ) {
		$diff_rate = 0;
	}elsif( $arg eq '-rate' ) {
		$diff_rate = 1;
	}
	$i++;
};

open RES_FILE, ">$res_file" or die "open failed,$!\n";

print "SOURCE FILE IS $source_filename\n";
my $csv_sfh = Tie::Handle::CSV->new($source_filename, header => 1, key_case => 'any');

my $csv_sheader = $csv_sfh->header;
my @ay_csv_sheader = split( /,/, $csv_sheader );
print "CSV HEADER IS $csv_sheader\n";

if( $diff_rate == 0 ){
	print "no rate diff\n";
	if( ( $csv_sheader !~ m/duration/i ) || ( $csv_sheader !~ m/dnis/i ) ){
		print "SOURCE FILE $source_filename HEADER ERROR;MUST CONTAINS duration and dnis\n";
		exit;
	}
}
if( $diff_rate == 1 ){
	print "rate diff\n";
	if( ( $csv_sheader !~ m/duration/i ) || ( $csv_sheader !~ m/dnis/i ) || ( $csv_sheader !~ m/rate/i ) || ( $csv_sheader !~ m/cost/i ) ){
		print "SOURCE FILE $source_filename HEADER ERROR;MUST CONTAINS duration and dnis and rate\n";
		exit;
	}
}

print "DIFF FILE IS $diff_filename\n";
my $csv_dfh = Tie::Handle::CSV->new($diff_filename, header => 1, key_case => 'any');

my $csv_dheader = $csv_dfh->header;
my @ay_csv_dheader = split( /,/, $csv_dheader );
print "CSV HEADER IS $csv_dheader\n";

if( $diff_rate == 0 ){
	print "no rate diff\n";
	if( ( $csv_dheader !~ m/duration/i ) || ( $csv_dheader !~ m/dnis/i ) ){
		print "DIFF FILE $diff_filename HEADER ERROR;MUST CONTAINS duration and dnis\n";
		exit;
	}
}
if( $diff_rate == 1 ){
	print "rate diff\n";
	if( ( $csv_dheader !~ m/duration/i ) || ( $csv_dheader !~ m/dnis/i ) || ( $csv_dheader !~ m/rate/i ) ){
		print "DIFF FILE $diff_filename HEADER ERROR;MUST CONTAINS duration and dnis and rate\n";
		exit;
	}
}

#my @ay_scdr = ( 1, 2, 3, 4, 5 );
##print "$ay_scdr[0]\n";
#my %hd_scdr = ( a => 1, b => 2 );
##print "$hd_scdr{a}\n";
#
#my $ay_scdr_ref = [ 1, 2, 3, 4, 5 ];
##print "${$ay_scdr_ref}[0], $ay_scdr_ref->[0]\n";
#my $hd_scdr_ref = { a => 1, b => 2 };
##print "${$hd_scdr_ref}{a}, $hd_scdr_ref->{a}\n";

#my $ay_scdr_ref = [];
#my $tmp_ref = [];

print RES_FILE "$csv_sheader\n";
print "\n";
my $hd_scdr_ref = { "", {} };
my $scdr_count = 1;
while (my $csv_sline = <$csv_sfh>) {
	for my $head_name ( @ay_csv_sheader ){
		$hd_scdr_ref->{ $scdr_count }->{ $head_name } = $csv_sline->{ $head_name };
	}
	$hd_scdr_ref->{ $scdr_count }->{ 'is_find' } = '0';
	$hd_scdr_ref->{ $scdr_count }->{ 'diff_line' } = '0';
	$scdr_count++;
}

my $hd_dcdr_ref = { "", {} };
my $dcdr_count = 1;
while (my $csv_sline = <$csv_dfh>) {
	for my $head_name ( @ay_csv_dheader ){
		$hd_dcdr_ref->{ $dcdr_count }->{ $head_name } = $csv_sline->{ $head_name };
	}
	$hd_dcdr_ref->{ $dcdr_count }->{ 'is_find' } = '0';
	$dcdr_count++;
}

my $k1 = 1;
while( $k1 <= $scdr_count ) {
	print "source cdr : $hd_scdr_ref->{ $k1 }->{ 'dnis' },$hd_scdr_ref->{ $k1 }->{ 'duration' }\n";
	if( $hd_scdr_ref->{ $k1 }->{ 'duration' } !~ m{\d+} ){
		$k1++;
		next;
	}
	my $k2 = 1;
	while( $k2 <= $dcdr_count ){
		if( $hd_dcdr_ref->{ $k2 }->{ 'duration' } !~ m{\d+} ){
			$k2++;
			next;
		}
		if( ( $hd_dcdr_ref->{ $k2 }->{ 'dnis' } == $hd_scdr_ref->{ $k1 }->{ 'dnis' } ) && ( $hd_dcdr_ref->{ $k2 }->{ 'is_find' } == '0' ) ){
			if( ( abs $hd_dcdr_ref->{ $k2 }->{ 'duration' } - $hd_scdr_ref->{ $k1 }->{ 'duration' } ) <= $time_diff ){
				if( ($diff_rate == 1) && ( $hd_dcdr_ref->{ $k2 }->{ 'rate' } != $hd_scdr_ref->{ $k1 }->{ 'rate' } ) ){
					$hd_scdr_ref->{ $k1 }->{ 'is_find' } = '3';
					$hd_scdr_ref->{ $k1 }->{ 'diff_line' } = $k2;
				}else{
					$hd_scdr_ref->{ $k1 }->{ 'is_find' } = '1';					
					$hd_dcdr_ref->{ $k2 }->{ 'is_find' } = '1';
					last;
				}
			}else{
				$hd_scdr_ref->{ $k1 }->{ 'is_find' } = '2';
				$hd_scdr_ref->{ $k1 }->{ 'diff_line' } = $k2;
			}
		}
		$k2++;
	}
	print "is_find : $hd_scdr_ref->{ $k1 }->{ 'is_find' }\n";
	if( $hd_scdr_ref->{ $k1 }->{ 'is_find' } != '1' ){
		if( $hd_scdr_ref->{ $k1 }->{ 'is_find' } == '0' ){
			for my $head_name ( @ay_csv_sheader ){
				print RES_FILE "$hd_scdr_ref->{ $k1 }->{ $head_name },";
			}
			print RES_FILE "not find,";
		}elsif( $hd_scdr_ref->{ $k1 }->{ 'is_find' } == '2' ){
			for my $head_name ( @ay_csv_sheader ){
				print RES_FILE "$hd_scdr_ref->{ $k1 }->{ $head_name },";
			}
			print RES_FILE "duration diff,";
			for my $head_name ( @ay_csv_dheader ){
				print RES_FILE "$hd_dcdr_ref->{ $hd_scdr_ref->{ $k1 }->{ 'diff_line' } }->{ $head_name },";
			}
		}elsif( $hd_scdr_ref->{ $k1 }->{ 'is_find' } == '3' ){
			for my $head_name ( @ay_csv_sheader ){
				print RES_FILE "$hd_scdr_ref->{ $k1 }->{ $head_name },";
			}
			print RES_FILE "rate diff,";
			for my $head_name ( @ay_csv_dheader ){
				print RES_FILE "$hd_dcdr_ref->{ $hd_scdr_ref->{ $k1 }->{ 'diff_line' } }->{ $head_name },";
			}
		}
		print RES_FILE "\n";
	}
	$k1++;
}

close $csv_sfh;
close $csv_dfh;

close RES_FILE;