#!/usr/local/bin/perl -w
#File:sip_scenario.pl
require 5.000;
use strict; 
my($g_version); $g_version ="1.2.7";
my $g_displayVersion="SIP Scenario Generator version=$g_version\n";
################################################################################################################
#	Legal Section
################################################################################################################
my $license= '
################################################################################################################

The SIP Scenario Generator Software License, Version 1.1

Copyright (c) 2003 IPC Information Systems Inc.  All rights reserved.

Redistribution and use in source and binary forms, with or without modification, 
are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, 
this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, 
this list of conditions and the following disclaimer in the documentation 
and/or other materials provided with the distribution.

3. The end-user documentation included with the redistribution, 
if any, must include the following acknowledgment:

"This product includes software developed by 
IPC Information Systems Inc (http://www.ipc.com/)."

Alternately, this acknowledgment may appear in the software itself, 
if and wherever such third-party acknowledgments normally appear.

4. The names "SIP Scenario Generator" and "IPC Information Systems Inc" 
must not be used to endorse or promote products derived from this software 
without prior written permission. For written permission, please contact 
the legal department at IPC Information Systems Inc.

5. Products derived from this software may not be called "SIP Scenario Generator", 
nor may "SIP Scenario Generator" appear in their name, without prior written 
permission of IPC Information Systems Inc.

THIS SOFTWARE IS PROVIDED ``AS IS`` AND ANY EXPRESSED OR IMPLIED WARRANTIES, 
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND 
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL 
IPC INFORMATION SYSTEMS INC OR ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF 
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

This software consists of voluntary contributions made by 
Ray Elliott of IPC information System Inc. e-mail:ray.elliott@ipc.com
please see <http://www.ipc.com/>.

################################################################################################################
';
my $about= '
################################################################################################################

The Sip Scenario Generator Program was created and designed by Ray Elliott of IPC Information Systems Inc.
SIP Scenario Generator Version='.$g_version.'
 
E-mail Contact is Ray.Elliott@ipc.com

If Problems reports are being submitted please include the corresponding ethernet trace file.

################################################################################################################
';
################################################################################################################
#Author: Ray Elliott	Contact:ray.elliott@ipc.com
#Contributors Section:
#Ray Elliott	Contact:ray.elliott@ipc.com
#Modification Section
################################################################################################################
################################################################################################################
#	PROGRAM DESCRITION
################################################################################################################
#
my $description= '
################################################################################################################

This program makes SIP callflows (scenarios) diagrams from an ethernet trace.
The program reads the libpcap output format created by ethereal, tcpdump, etc) and creates sip scenario (call flows).
There are three basic outputs:
	A printable text file.
		BASENAME.txt 			a printable version of XXXX.html
	A single HTML File version
		BASENAME.html	 		a basic html file with reference to the expanded SIP messages.
	An HTML version using frames. (has 2 files).
		BASENAME_index.html	 	a basic html file that has the frame definitions
		BASENAME_indexhtml.html	 	an html file with with references using frames.

Each ethernet packet that is contained in the libpcap trace file is called a physical Frame. Each packet is given a sequence number
call the Physical Frame number. The Physical Frame Number is used for Documentations as a reference to a fixed location.

Each SIP message that is display is identified by a sequential number call the SIP Frame Number.

All UDP and TCP packets will be will be parsed to check if there are SIP messages or not.
Non-SIP messages will be automatically filtered out of the display.
		
Different SIP calls (Based on CALLID)  will be indicated in different colors.
Links are made from the sip scenario (call fow) to the actual SIP Message (frame data).

detects and displays hold / nomedia for messages with content-type=application/sdp
detects and displays different content types on sip scenario
displays a list a reasons that ethernet packets in trace file were not displayed.

Has various display options.
Has several mechanism to filter Sip Messages or Physical frames.


	Basic Output Document Format is 
	###################################
	Title and Info Section
	###################################
		Scenario Title
		File: trace.dump
		Generated: Sat Mar  1 20:30:10 2003
		Traced on: Fri Feb 28 16:30:30 2003
		Created by:../sip_scenario.pl version=1.0.39
	###################################
	Scenario Trace
	###################################
		Proxy              Turret 1           Phone2
		10.70.200.148      10.70.200.195      10.70.200.218
		|                  |                  |  
		|                  |                  |
		|<--------------------(sdp) INVITE F1<|  
		|>F2 100 Trying --------------------->|  
		|>F3 INVITE (sdp)->|                  |  
		|>F4 INVITE (sdp)-------------------->|  
		|<-(sdp) OK 200 F5<|                  |  
		...
	###################################
	Detail Frame Information
	###################################
		    SIP Frame     1    10.70.200.218:1067(Pingtel) -> 10.70.200.148:5060(Proxy)
		Physical Frame    3    28/Feb/03 16:30:31.5593 TimeFromPreviousSipFrame=0 TimeFromStart=0 
		INVITE sip:2111@10.70.200.148 SIP/2.0
		From: sip:2110@10.70.200.148;tag=2c497
		To: sip:2111@10.70.200.148
		Call-Id: call-1046467828-28@10.70.200.218
		Cseq: 1 INVITE
		...
	###################################
	Miscellaneous Information
	###################################

	###################################
	End Of Document

Note:
This program can generate html pages that are too big for browsers. When this occurs there are several things that can be done.
1) Remove the extended frame data so that the basic SIP scenario can be viewed. use -format:frames:0 command
2) Use the include/exclude Callid commands -include:callid: or -exclude:callid: to reduce the calls
3) Use the -range command to reduce the input.
After the set of calls have been reduced, then add the related extended frame data by removing the command -format:frames:0
################################################################################################################
';

# This program was developed using active perl 5.6.1 on a PC running window 2000.
# It was designed so that it should run on solaris and linux system.
################################################################################################################
#	Syntax
################################################################################################################
#
my $helpstr=$g_displayVersion.'
quick help
sip_scenario  filename eg. sip_scenario ethereal_trace.dump

sip_scenario  help | -help | -syntax | -syntax++ | -description | -v[ersion] | -l[icenseinfo] | -about | Parameters
help | - help			Displays help Message
-syntax 			Displays basic syntax
-syntax++			Displays Extended syntax information
-de[scription]			Displays a description of the program
-v[ersion]			Displays the version of this program
-l[icenseinfo]			Displays licensing information
-about				Displays information by program and where to send problem reports
';

my $syntax=$helpstr.'
Parameters 		:=	inputFilename  | -oBaseOutfilename           optionalParameters
optionalParameters 	:=	optionalParameters | [optionalParameter]
optionalParameter 	:= 
IPADDRESS[:PORT}[/ALIAS][:NUMBER]][:singleua] |	# Defines an IP address with alias and column position
-t[itle]:TITLE | 				# Defines Title
-g[ap]:NUMBER | 				# defines the gap between columns
-de[scription]:NUMBER | 			# Enable/Disable Display of long Sip Scenario Descriptions
-ver[ify]:NUMBER | 				# Enable/Disable verify all call partcipants in columns
-i[nclude][:NUMBER]:INCLUDEFILE | 		# Defines Include file and number of lines to skip at the start of the include file

Filter Commands
-ports:udp|tcp:LIST | 				# defines set of udp/tcp used to filter SIP messages
-r[ange]:RANGELIST | 				# Range of Physical Frames to include
-i[nclude]:line:RANGELIST | 			# Range of Physical Frames to include
-e[xclude]:[line:]RANGELIST | 			# defines list of physical frames to exclude.
-i[nclude]:t[ime]:STARTTIME-ENDTIME | 		# Range of Physical Frames to include based on a time range 
-i[nclude]:c[allid]:RANGELIST | 		# defines set of calls to include
-e[xclude]:c[allid]:RANGELIST | 		# defines set of calls to exclude
-i[nclude]:e[xpression]:PERLPATTERN | 		# defines a set of calls to include by matching any header in the call with the PERLPATTERN
-e[xclude]:e[xpression]:PERLPATTERN | 		# defines a set of calls to exclude by matching any header in the call with the PERLPATTERN
-i[nclude]:req[uest]:REQUEST |	 		# defines a set of calls to include by matching any SIP REQUEST in the call with REQUEST
-e[xclude]:req[uest]:REQUEST |	 		# defines a set of calls to exclude by matching any SIP REQUEST in the call with REQUEST
-i[nclude]:m[atch]:STRING | 			# defines a set of calls to include by matching any header in the call with the STRING
-i[nclude]:ip:IPADDRESS | 			# includes any call that has any SIP packet with the IP header containing the specified IPADDRESS
-e[xclude]:ip:IPADDRESS | 			# excludes any call that has any SIP packet with the IP header containing the specified IPADDRESS

Format Commands
-f[ormat]:c[allid]:NUMBER | 			# defines display format for callid 0-2
-f[ormat]:t[ime]:NUMBER | 			# defines display format for time   0-15
-f[ormat]:f[rames]:NUMBER | 			# defines display format for Adding extended frame Data  0-1
-f[ormat]:p[hy]:NUMBER | 			# defines display format for Physical Frame Number  0-1
-f[ormat]:v[ertical]:NUMBER | 			# defines vertical spacing 0-3 0==>most compressed   3==> least compressed
-f[ormat]:s[pacetime]:N/Q | 			# defines extra spacing based on time    N=Seconds Q=#of lines to add
-percent:NUMBER |	 			# defines the percent of vertical space allocated to the bottom frame in the index.html file.

Miscellaneous Commands
-re[order]:RANGELIST:FRAME |			# reorders a set of sip messages.
-c[ommentprefix]:XSTRING: | 			# defines characters that are preappend to each comment line.
-c[omment]:NUMBER:COMMENT | 			# defines a comment line
-fake:SRCIP:DSTIP:CALL#:PFRAME:STRING | 	# defines user defines SIP message.
-colors:COLORLIST | 				# defines set of colors to use
-stat:NUMBER |					# Turn on/off displaying statistics
-copy |						# special copy function
-merge:FILENAME2 |				# Merge capture file2 with 1st capture file, 
						  automatic time synchronization and removes duplicate pkts
-debug:NUMBER |					# debug. Add extra information for debugging.
-ker[beros][:NUMBER] |				# enable/disable kerberos
-summary[:FILENAME] |				# displays a summary of ip adrress used and calls.
-keep:NUMBER					# the set of files to keep
-singleua					# disable symmeteric UDP port detection for all IPADDRESSES.

-NoPauseOnError				        # disables pause on error feature (enabled by default)
-PauseOnError				        # Enables pause on error feature (enabled by default)

STRING 			:= is a non-empty string of ascii characters (0x20-0x7f)
XSTRING			:= is a non-empty string of ascii characters (0x20-0x7f) excluding ":"
ALIAS			:= XSTRING
TITLE			:= STRING
SRCIP,DSTIP		:= IPADDRESS | XSTRING
CALL#			:= NUMBER | XSTRING
PFRAME			:= NUMBER [. NUMBER]
NUMBER			:= a decimal number >= 0
IPADDRESS		:= NUMBER.NUMBER.NUMBER.NUMBER
RANGELIST		:= NUMBER | NUMBER-NUMBER | [,RANGELIST] | <nothing>
LIST			:= NUMBER | [,LIST]
COLORLIST		:= Black|Green|Silver|Lime|Gray|Olive|White|Yellow|Maroon|Navy|Red|Blue|Purple|Teal|Fuchsia|Aqua | #RRGGBB | [,COLORLIST]
RR,GG,BB		:= TWO HEX DIGITS representing the (Red,Green,Blue) color component
PERLPATTERN 		:= STRING
TIME			:= [[[YEAR/]MM/]DD/]HH:MM
FILENAME		:= STRING must be a valid file name.

################################################################################################################
';
#
################################################################################################################
#	parameter definitions
################################################################################################################
#
my $extendedParameters='
################################################################################################################


   inputFilename is a libpcap formatted ethernet trace file.
   	The inputfile name is an optional parameter since fake sip messages can be generated.

   -oBaseOutfilename defines the path and filename (not including the extension) for the output.
   	The extension added is based on the output mode selected.
	the default BaseOutfilename is derived from the inputFile name. It is the filename without extensions or directory information.
	If not specified the output path name is the current directory.
	if a directory name is specified, without the filename (ending with a "/" or "\" or ":") 
	then output will but placed in the specified directory

   IPADDRESS[:PORT}[/Alias}[:newpos][:singleua]
   	IPADDRESS defines the IP address for a column.
   	PORT defines a specific port for an ip address for a column.
	Alias can be assigned to the ipaddress. This alias is used for display purposes as column headings
   		they can also be used to generate SIP messages (see -fake command)
		Alias must be unique. Two different ip address can not have the same alias.
  	newpos is the column number to assign the IP address / alias.
  		newpos=0 ==>	IP address will be remove a defined IP address
		IP addresses can be defined as a parameter or automatically if encountered in the the trace file.
		Only IP addresses that are found in the trace file will be columns in the in the scenario output.
	singleua specifies that the is only one SIP ua at IPADDRESS. disables symmetric udp port detection for that IPADDRESS
  	there is no limitation to the number of IP addresses that can be defined
  	examples
  	10.70.200.218
  	10.70.200.195/Chris			## Add alias of Chris
  	10.70.200.196/Leo:2			## Add alias of Leo and inserts in the second position in front of the exist position 2
  						## if there is no position 2 then it is placed at the end of the list
  	10.70.200.218:0				## deletes ip address from the list. 
  	10.70.200.196:5060/Leo:2		## Add alias of Leo and inserts in the second position in front of the exist position 2
  						
  	Note:10.70.200.218:N	Allows for a common list of ip addresses and aliases. Each specific SIP scenario trace can
  		modify the order of the ip address assigned to the columns.

   -singleua					# disable symmeteric UDP port detection for all IPADDRESSES.
   
   -title:TITLE Sets the title of the sip scenario.
   	default is :Sip Scenario Trace
   	note: the TITLE must contain non-numeric characters

   -g[ap]:NUMBER set the number of spaces between columns
   	e.g.  -g18
		10.63.200.218      10.63.200.148      10.63.200.195    
		|                  |  (sdp) INVITE F1 |
   	e.g.  -g28
		10.63.200.218                10.63.200.148                10.63.200.195    
		|                            |            (sdp) INVITE F1 |

   -de[scription]:N Sip Message descriptions that do not fit between the arrowhead start and the arrow feathers will be truncated
   	An extra line with the full description will be inserted. 
   	where N=
   	0==> no extra line
		|                  |                  |
		|>F20 481 Transact>|                  |                  |  Call#:1
   	1==> extra line  (default)
		|                  |                  |
		| F20 481 Transaction Does Not Exist  |                  |
		|>F20 481 Transact>|                  |                  |  Call#:1

   -ver[ify]:N When verify is enabled, insures that all partcipants of a scenario are included in a SIP scenario. Enabled by default.
	If Ip addresses have been removed (All SIP Ip addresses are included by default) 
		and verify is off then SIP messages for that IP address will not be included.
	The only purpose for verify to be turned off is special documentation purposes. It should not be used
	when debugging traces.
   	The following example IP address 10.63.200.200 has been remove, but is a part of a scenario.
	The vertical column bars and arrow heads removed and extra infomration is added at the end of the trace.
   	This is to make sure SIP messages are not ignored. This is especially important for debugging.
   		Sometimes removing these message is required for documentation purposes.

   	0==> no inclusion. no verification. only include a trace if both IP addresses are defined in a column.
		|<--------------------(sdp) INVITE F1<|  Call#:1
		|>F2 100 Trying --------------------->|  Call#:1
		|>F3 INVITE (sdp)->|                  |  Call#:1
		|<-(sdp) OK 200 F4<|                  |  Call#:1

   	1==> Include SIP Messages with same Callid even though IP address has been removed for a column (default)
		|<--------------------(sdp) INVITE F1<|  Call#:1
		|>F2 100 Trying --------------------->|  Call#:1
		|>F3 INVITE (sdp)->|                  |  Call#:1
		|                  |                  |
		  F4 INVITE (sdp)                        Call#:1 [10.63.200.148:5060==>10.63.200.220:5060] IP_ADDR 10.63.200.220 excluded from columns
		|<-(sdp) OK 200 F5<|                  |  Call#:1

   -i[nclude][:N:]INCLUDEFILE allows file includeFile to be read as a set of commands
   	N is the number of lines to skip from the beginning of the include file.
   	The following this the logic for parsing each line of an include file.
   	*) The Comment delimiter is the "#". Characters starting with the "#" (and including the #) will be considered white space.
   	*) white space will be removed from the begining and end of each line
   	*) Lines ending with white space backslash  or "\" will be joined with the next line. 
   		The back slash will be removed and white space removed from the end of line
   	*) Lines that start with "perl" ,"exit" , "rem" or "sip_scenario" will also be ignored. 
   		This allows a batch file on a pc to trigger the script file and
   	*) A line that starts with end-of-file will cause the rest of the file to be ignored.
   	e.g. -i:includeFIle
   	e.g. -i:2:includeFIle		ignores the first 2 lines from file includeFile
   	
   		and to have that batch file be a sip_scenario.pl include file.
   		e.g.
   		===================================== Start of File sip_scenario.ini =======================
		# this file defines user preferences
		10.70.200.215/Leo
		10.70.200.218/Sam 
		10.70.200.196/George
		10.70.200.148/Proxy 
		10.70.200.195/Xian
		10.70.200.211/Corey
		10.70.200.220/Jeff
		10.70.200.79/Ray`s Laptop 
		10.73.200.150/Gateway
		10.70.200.227/Chris
   		===================================== End of File ==================================
   		or
   		===================================== Start of File doit.bat =======================
   		perl  sip_scenario.pl -I:2:doit.bat
		exit
		dump/trace_forking.dump		 	# input file
		-oipc_call_setup.html 			# output file
		-tRegistrations				# title
		-g30 					# gapwidth
		-phy:1  					# display physical frame numbers
   		===================================== End of File ==================================
		or
   		===================================== Start of File doit.bat =======================
		perl  sip_scenario.pl -i:ipc_call_setup.bat
		exit
		
		## start of include file here
		dump/trace_forking.dump 		# input file
		-oipc_call_setup.html 			# output file
		-tTurret Callflow using IPC Proxy	# set title
		-gap:30					# set gapwidth between lines
		-f:p:1 					# enable physical Frames
		-f:s:2/4				# turn on insertions of extra spaces over 2 seconds add 4 blank lines
		-colors:Black,Blue,Red,Green		# define colors if required
		-exclude:3,4-32,35-39,42-53		# exclude the specified physical frames
		10.70.200.148/IPC PROXY			# redefine alias.
		
		-fake:10.70.200.148:10.70.200.195:0:2:SIP/2.0 402 Some Response\n\
			User-Agent:Turret 9.01.01.x\n\
			ContentType=application/IPC\n\
			\n\
			lac=1024\n
		
		-fake:10.70.200.148:10.70.200.196:0:2:SIP/2.0 402 Some Response\n\
			User-Agent:Turret 9.01.01.x\n\
			ContentType=application/IPC\n\
			\n\
			lac=1024\n
		
		end-of-file
		
   		===================================== End of File ==================================
	Basic Operation: Tailored for windows PC.
		File sip_scenario.ini: this file is an optional file and should contains user preferences
		File ip_address_def_n.include: One or more files that contain ip Address and alias information.
			The order of ip Address determines the order of ip addresses in the scenario trace.
			So multiple multiple may be required for multiple traces.
		user specific setting can also be added to this file to tailer the defaults for a user.
		File trace.bat:a batch file that invokes sip_scenario and customizes the output for that specific trace
			includes:
			A. the input and output file names
			B. title of the document
			C. Physical Frames to exclude.
			D. Reorder physical Frames
			E. Fake (user defined) Frames
			F. Other document specific item.

   		to execute this file from a window command execute
   		cmd /c doit.bat	
   		which creates a new command window executes the batch file doit.bat,
   		doit.bat executes the command perl ... which includes the file doit.bat
   			The sip_scenario.pl script file read doit.bat and will ignore the
   			first two lines.
   		doit.bat then exits and close the window create the command \"cmd\".
   	Note: if file \"sip_scenario.ini\" is present then it will be included before any other parameters are parsed - even from the command line.

   -ports:protocol:LIST Change the list of ports to listen on. the default value is 5060.
   	LIST is a comma separate list of port numbers. 
	e.g. -ports:udp:5060,5061		set ports to trace for udp = 5060 and 5061
	e.g. -ports:tcp:5060,5061		set ports to trace for tcp = 5060 and 5061

   -range:RANGELIST specifies the set of physical frames that will be read.
   	This is designed for very large trace files that can not be otherwise handled by this
   	implementation. Can also be used for filtering.
	packets excluded will not be parsed.
	same as -include:line:RANGELIST
   	RANGELIST is a comma separate list of ranges or individual physical frame numbers ,,1-5,2,3,4,10-12   is a valid LIST
   	e.g.    -range:1-2,10-20,4-5

    -i[nclude]:line:RANGELIST specifies the set of physical frames that will be read.
   	This is designed for very large trace files that can not be otherwise handled by this
   	implementation. Can also be used for filtering.
	same as -range:RANGELIST
   	RANGELIST is a comma separate list of ranges or individual physical frame numbers ,,1-5,2,3,4,10-12   is a valid LIST
   	e.g.    -include:line:3,10-20,4-5

    -e[xclude]:[line:]RANGELIST specifies the set of physical frames that will not be used to generate
    	SIP Scenarios. The -range command will filter frames before this command.
   	RANGELIST is a comma separate list of ranges or individual physical frame numbers ,,1-5,2,3,4,10-12   is a valid LIST
   	-excludes the defined set of physical sip messages from being placed in the scenario.
   	e.g.    -exclude:1,3,10-20,4-5

    -i[nclude]:t[ime]:STARTTIME-ENDTIME | 		Range of Physical Frames to include based on a time range
    	packets outside this time range are immediately discarded and will not be parsed.
        TIME			:= [[[YEAR/]MM/]DD/]HH:MM[:SS]
	Year is the actual year. e.g. 2003		Year is optional
	Month has a range 1-12				Month is optional
	Day has a range 1-31				Day is optional
	Hour has a range 0-23
	Minutes have a range of 0-59
	Seconds have a range of 0-59
	STARTTIME: default values for YEAR,MM (Month), and DD (day) will the the Year,month, and day of the first traced packet.
	ENDTIME: default values for YEAR,MM (Month), and DD (day) will the the Year,month, and day of the STARTTIME
	use the -debug option to debug problems.
    	
    -i[nclude]:c[allid]:RANGELIST specifies the set of Calls that will be included.
    	This is an easy and save way to filter out SIP packets. It is easy to filter of registrations just by excluding a single callid.
    	Call Id are numbered sequentially based on the SIP CALLID field. CallIDs are unique just like a SIP CALLID.
	In large traces in is necessary to limit the display to a certain set of calls. 
	The "-include:callid" and the "-exclude:callid" provide that ability.
	Initially all calls are included.
	If the first include/exclude command is an include then the set is changed to the set described by that include command.
	From then on exclude commands will remove calls from the list and include commands will add calls to the list.
	There is no restriction as to order of includes or excludes.
	There is no restriction of order in the RANGELIST, with the exception that the start of a range must not be greater than the end.
	-include:callid:1,50,20-30

   -e[xclude]:c[allid]:RANGELIST specifies the set of Calls that will be excluded.
    	see -include:callid for details.
	-exclude:callid:1,50,20-30

    -i[nclude]:req[uest]:REQUEST  		defines a set of calls to include by matching any SIP REQUEST in the call with REQUEST

    -e[xclude]:req[uest]:REQUEST 	 	defines a set of calls to include by matching any SIP REQUEST in the call with REQUEST

    -i[nclude]:m[atch]:STRING		  	defines a set of calls to include by matching any header in the call with the STRING
    						same as -include:expression

    -i[nclude]:e[xpression]:PERLPATTERN  	defines a set of calls to include by matching any header in the call with the PERLPATTERN
	Any Header that matches the PERLPATTERN will cause all SIP messages in that call to be included.
	Multiple arguments of -include:expression works as follows: the last one process will be executed.  Only one instance is supported. 
        PERLPATTERN is a "Perl" Language pattern to match. See Perl documentation for details of matching and perl regular expressions
	http://www.perldoc.com/perl5.6/pod/perlre.html 

	Here are some defintions of perl metacharacter characters. 
	^	Matches start of line
	$	Matches end of line
	|	Logical Or
	\w  	Match a "word" character (alphanumeric plus "_")
    	\W  	Match a non-word character
    	\s 	Match a whitespace character
    	\S  	Match a non-whitespace character
    	\d  	Match a digit character
    	\D  	Match a non-digit character
	\X	escapes next character. same as X    		e.g. \\ evaluates to \
	\b	Matches a word boundary
	.	Matches any character.
	+	Preceding character or expression must occur one or more times
	*	Preceding character or expression must occur zero or more times
	?	Preceding character or expression must occur zero or one times
	{N,M}	Preceding character or expression must occur at Least N times but not more than M times
	()	Grouping
	(?i)	Add to start of String if case-insenative matching is desired.
	\s*	Matches any white space string. Empty allowed.
	\w+	Matches a word
    	Examples:
	-include:expression:(?i)^(from|to).*George\@			## include calls with george@ contained in From or To headers
	-include:expression:(?i)^(from|to).*george\@ipc\.com		## include calls with george@ipc.com contained in From or To headers
	-include:expression:(?i)^to.*george\@				## include calls with george@ contained in To headers
	-include:expression:presence					## include calls that have the characters presence in some header
	-include:expression:(?i)\bpresence\b				## include calls that have the word presence in some header
	-include:expression:(?i)^Allow-Events\s*:\s*presence\s*$	## include calls that have the header "allow-events : presence" 
	-i:e:^REFER\s+							## include calls with a REFER request

   -e[xclude]:e[xpression]:PERLPATTERN  	Similar to -include:expression except that calls are excluded. 

   -i[nclude]:ip:IPADDRESS 
   	includes any call that has any SIP packet with the IP header containing the specified IPADDRESS
	-include:ip:10.1.2.206						## include calls that have ip address = 10.1.2.206
	-i:ip:10.1.2.206						## include calls that have ip address = 10.1.2.206

   -e[xclude]:ip:IPADDRESS 
   	excludes any call that has any SIP packet with the IP header containing the specified IPADDRESS
	-exclude:ip:10.1.2.206						## exclude calls that have ip address = 10.1.2.206
	-e:ip:10.1.2.206						## exclude calls that have ip address = 10.1.2.206

   -f[ormat]:c[allid]:NUMBER          controls the inclusion of CallId of the scenario Trace
   	default n=1;
   	where N=
   	0==> No call Id
		|>F22 ACK --------------------------->|  
		|<---------------------- REGISTER F23<| 
		|>F24 200 OK ------------------------>| 
   	1==> call Id with no Descriptor (default)
		|>F22 ACK --------------------------->|  1
		|<---------------------- REGISTER F23<|  2
		|>F24 200 OK ------------------------>|  2
   	2==> call Id with Descriptor
		|>F22 ACK --------------------------->|  Call#:1
		|<---------------------- REGISTER F23<|  Call#:2
		|>F24 200 OK ------------------------>|  Call#:2

   -f[ormat]:t[ime]:NUMBER           controls the inclusion of time display on the scenario trace.
       Time is based on the absolute time contained in the libpcap trace input file.
       Delta time can be negative when Physical frames are reordered for display purposes.
       The time display is controled by bits. Add the bits together to get the desired time display.
       1==> Delta Time	(default) (from previous displayed packet)
		10.63.200.218      10.63.200.148      10.63.200.195      10.63.200.196
		|                  |                  |                  |  <DeltaTime>
		|>F1 INVITE (sdp)->|                  |                  |  0     
		|<-- Trying 100 F2<|                  |                  |  0.0013
		|                  |>F3 INVITE (sdp)->|                  |  0.0014
		|                  |>F4 INVITE (sdp)-------------------->|  0.0004
       2==> Relative Time (from first packet)
		10.63.200.218      10.63.200.148      10.63.200.195      10.63.200.196
		|                  |                  |                  |  <RelTime>
		|>F1 INVITE (sdp)->|                  |                  |  0     
		|<-- Trying 100 F2<|                  |                  |  0.0013
		|                  |>F3 INVITE (sdp)->|                  |  0.0027
		|                  |>F4 INVITE (sdp)-------------------->|  0.0040
       4==> Time of Day  (absolute time)
		10.63.200.218      10.63.200.148      10.63.200.195      10.63.200.196
		|                  |                  |                  |  <Time>
		|>F1 INVITE (sdp)->|                  |                  |  16:30:31.5593
		|<-- Trying 100 F2<|                  |                  |  16:30:31.5606
		|                  |>F3 INVITE (sdp)->|                  |  16:30:31.5621
		|                  |>F4 INVITE (sdp)-------------------->|  16:30:31.5633
       8==> Date 
		10.63.200.218      10.63.200.148      10.63.200.195      10.63.200.196
		|                  |                  |                  |  <Date>
		|>F1 INVITE (sdp)->|                  |                  |  28/Feb/03
		|<-- Trying 100 F2<|                  |                  |  28/Feb/03
		|                  |>F3 INVITE (sdp)->|                  |  28/Feb/03
		|                  |>F4 INVITE (sdp)-------------------->|  28/Feb/03
   	where N=
       0==> No time
       1==> Delta Time	(default) (from previous displayed packet)
       2==> Relative Time (from first packet)
       3==> Delta Time	 and Relative Time
       4==> Time of Day  (absolute time)
       5==> Both Delta time  and Time of Day 
       6==> Relative Time and Time of Day  
       7==> Delta time, Relative time, and Time of Day 
       ...
       12==> date and time
		10.63.200.218      10.63.200.148      10.63.200.195      10.63.200.196
		|                  |                  |                  |  <Date><Time>
		|                  |                  |                  |
		|>F1 INVITE (sdp)->|                  |                  |  28/Feb/03 16:30:31.5593
		|<-- Trying 100 F2<|                  |                  |  28/Feb/03 16:30:31.5606
		|                  |>F3 INVITE (sdp)->|                  |  28/Feb/03 16:30:31.5621
		|                  |>F4 INVITE (sdp)-------------------->|  28/Feb/03 16:30:31.5633
       15==> Delta, Relative, and date and time
		10.63.200.218      10.63.200.148      10.63.200.195      10.63.200.196
		|                  |                  |                  |  <DeltaTime><RelTime><Date><Time>
		|                  |                  |                  |
		|>F1 INVITE (sdp)->|                  |                  |  0      0      28/Feb/03 16:30:31.5593
		|<-- Trying 100 F2<|                  |                  |  0.0013 0.0013 28/Feb/03 16:30:31.5606
		|                  |>F3 INVITE (sdp)->|                  |  0.0014 0.0027 28/Feb/03 16:30:31.5621
		|                  |>F4 INVITE (sdp)-------------------->|  0.0004 0.0040 28/Feb/03 16:30:31.5633

   -f[ormat]:p[hy]:NUMBER 
       Adds physical frames numbers to scenario diagram. The physical Frame number is the location of the packet in the ethernet trace file.
   		0==> disable display of physical frame numbers
			|>F1 INVITE (sdp)->|                  |                  | 
			|<-- Trying 100 F2<|                  |                  | 
   		1==> enable display of physical frame numbers
			|>F1 INVITE (sdp)->|                  |                  | PF3  
			|<-- Trying 100 F2<|                  |                  | PF4  

   -f[ormat]:f[rames]:NUMBER       controls the inclusion of the extended frame data or not. 
   	Extended Data is the set of displayed SIP messages including their content
   	where N=
   	0==> No Extended Frame data included.
		When no extended frame data is included then the index.html and indexhtml.html are not created.
   	1==> Extended Frame Data included.   (default)

   -f[ormat]:v[ertical]:NUMBER 
	set the vertical compression mode to value N, where N is 0,1, 2 or 3
   	where N=
   	0==> description and arrow on the same line
		|<--------------------(sdp) INVITE F1<|  
		|>F2 100 Trying --------------------->|  
   	1==> description and arrow on the same line with extra blank line  (default)
		|                  |                  |
		|<--------------------(sdp) INVITE F1<|  
		|                  |                  |
		|>F2 100 Trying --------------------->|  
   	2==> no blank lines	
		|                  |  (sdp) INVITE F1 |
		|<-----------------------------------<|  
		| F2 100 Trying    |                  |
		|>----------------------------------->| 
   	3==> extra blank line   
		|                  |                  |
		|                  |  (sdp) INVITE F1 |
		|<-----------------------------------<|  
		|                  |                  |
		| F2 100 Trying    |                  |
		|>----------------------------------->|  

   -f[ormat]:s[pacetime]:N/Q 
     set the number of seconds between traced messages so that Q extra blank lines can be added between messages.
   	N is a non-negative integer unit seconds
   	Q is a non-negative integer range unit number of blank lines to add.
   	This allows long periods of time to be indicated in the trace.
   	The default value is 2 seconds.
   	The value of zero disables this feature.
   	default: turned off
   	e.g. 
   	-f:s:0/0	## turn off feature
   	-f:s:5/8	## after 5 seconds difference in time betwwen two SIP messages insert 8 blank lines.

   -percent:NUMBER 
   	defines the percent of vertical space allocated to the bottom frame in the index.html file.
	-percent:50	## allocates 50% of the vertical space to frame data.
	Default value is 33 percent.

   -re[order]:LIST:FRAME 
   	LIST is a comma separate list of ranges or individual physical frame numbers ,,1-5,2,3,4,10-12   is a valid LIST
   	FRAME is the physical frame where the defined set of physical frames should be placed after.
   	-reorder reorder the order in which sip messages are displayed.  SIP frame numbers (F10) are resequenced automatically
   		Physical frame numbers are not changed and can be display with the -phy command
   	e.g.    -reorder1,2,3,7,10-20:4
   	e.g.    -reorder,1,2,3,7,10-20:4
   	e.g.    \"-reorder 1 , 2 ,3,7,10-20:4\"

   -c[omment]:FRAME:COMMENT Adds COMMENT after indicate physical Frame.		note: \n works
		e.g. -comment:0:User dials number 2111.

   -c[ommentprefix]:STRING: Adds STRING before all comment lines.  Note the extra : at the end of the STRING.
		e.g. -commentprefix:                            :

   -fake:SRCIP:DSTIP:CALL#:FRAME:MESSAGE	Add Fake Sip messages to the scenario. Increments the SIP Frame number
   	SRCIP==> Source Ip address | IpAddressAlias
   	DSTIP==> Destination Ip Address | IpAddressAlias
   	CALL#==>  CALL#
   		N==> Use CALLID for Call Number N. where is a number. 
   		W==> (alphanumeric string) Use Unique Callid W (Create it one first occurance)
   	FRAME==>  Physical Frame Number. Adds Message after the indicated Frame.
	MESSAGE==> Sip Message to Display    
		Multiple lines may be created and are separated by the delimiter \n.    note: this is much easier from an include file
		line1\nline2\nline3\n\nsdpline1\nsdpline2 ,....
	e.g.
	-fake:10.70.200.148:10.70.200.195:0:2:SIP/2.0 402 Some Response
	or
	-fake:10.70.200.148:10.70.200.196:0:2:SIP/2.0 402 Some Response\nUser-Agent:Turret 9.01.01.x\n
		
   -colors:COLORLIST Change the set of colors that are used to display scenarios.
   	LIST is a comma separate list of colors. 
   	a color is an valid html colors. The set of html colors is from the following list
   		Black,Green,Silver,Lime,Gray,Olive,White,Yellow,Maroon,Navy,Red,Blue,Purple,Teal,Fuchsia,Aqua 
   		or a color can be a user defined color in the form #RRGGBB
		where RR,GG,BB are each two hex digits e.g. #FF0080
	e.g. -colors:Black,Red,#808080

   -stat:NUMBER	This commands turns on/off the display of statistics in the output files.
   		if NUMBER is zero then statistics are disabled.
		if NUMBER is one then statistics are enabled. (default)

   -copy	This commands copies the input file to an output file with filtering.
   		Only TCP and UDP packets are copied.
		the -range command will limit the number of packets that are copied.
		the -x command will translate IP address in the IP headers (host formatted) 
			and in the TCP/UDP data (ascii dot notation).
		-x:IPADDRESS=IPADDRESS

   -merge:FILENAME2  This command Mergeis capture file2 with 1st capture file, 
		automatic time synchronization and removes duplicate pkts

   -debug:NUMBER turns on debug mode. This adds extra information to the output files.

   -ker[beros][:NUMBER] enable/disable adding kerberos protocol to trace
   		if NUMBER is not specified then kerberos tracing is enabled.
		NUMBER=0 disable kerberos tracing
		NUMBER!=0 ensable kerberos tracing


   -summary[:FILENAME] displays a summary of ip adrress used and calls.
   		If the FILENAME is not specified then the output is to STDOUT.
		IPADDRESS[:PORT]	ALIAS
		...
		IPADDRESS[:PORT]	ALIAS
		CALL 1 TIME FROM_USER TO_USER IPADDRESS[:PORT] ... IPADDRESS[:PORT]
		...
		CALL N TIME FROM_USER TO_USER IPADDRESS[:PORT] ... IPADDRESS[:PORT]

   -keep:NUMBER					# the set of files to keep
		bit defined 
		bit	value	Description
		0	1	Triple Frame (dual file) output	
				basefilename_index.html
				basefilename_indexhtml.html
		1	2	Single html file
				basefilename.html
		2	4	plain txt file.
				basefilename.txt
		Default value is 7 which keeps all files.
		
    -NoPauseOnError				# disables pause on error feature that forces a user
    						# to hit a key to see error message
						# NoPause on error should be first argument passed.
    -PauseOnError				# Enables pause on error feature that forces a user
    						# to hit a key to see error message

################################################################################################
# Applications
################################################################################################
*	Run Sip Scenario Generator while actually tracing ethernet data.
	tcpdump can directly capture ethernet data and copy it directly into a file. 
	Each time a packet is traced it is appended to the end of a file. The last packet in the
	file may not be completely written.

	On bsd 4.1 (intel)
	tcpdump -s 1514 -i fxp1 -w /tmp/sip.dump "port 5060" &

	On linux 7.3 I use:
	tcpdump -s 0 -i eth0 "port 5060" -w /var/log/sip1.dump &

	while the tcpdump is writing to the file sip_scenario.pl can be executed.
	The following command will find all calls that have any Sip header containing ray@ipc.com
	perl sip_scenerio.pl /var/log/sip1.dump  -include:match:ray@ipc.com
################################################################################################
# Changes History / Release information
################################################################################################
Date             Author            	Description
 8 Apr 2003      D. Ray Elliott     	Release 1.1.0
10 Apr 2003      D. Ray Elliott     	Added pattern Matching filters 
					-include:expression
					-include:match
11 Apr 2003      D. Ray Elliott     	Fixed libpcap format issues. Now have struct definitions 
					Created Bug list Item.
11 Apr 2003      D. Ray Elliott     	Release 1.1.1
11 Apr 2003      D. Ray Elliott     	Added filtering by Time
					-include:time:
					Allows packets to be included based on time
12 Apr 2003      D. Ray Elliott     	Release 1.1.2
18 Apr 2003      D. Ray Elliott     	Added handling of Compact forms of header names for
					Call-id, Content-type, and Content-Length headers.
18 Apr 2003      D. Ray Elliott     	Copied Time::Local into sip_scenario.pl file.
18 Apr 2003      D. Ray Elliott     	Release 1.1.4
22 Apr 2003      D. Ray Elliott     	Added filtering by ip address
22 Apr 2003      D. Ray Elliott     	Release 1.1.5
16 May 2003      D. Ray Elliott     	Handling Fragmented IP packets
17 May 2003      D. Ray Elliott     	allowed the -o options to be a directory name. 
25 Apr 2003      D. Ray Elliott     	Release 1.1.7
 4 Aug 2003      D. Ray Elliott     	-o options to be a directory name. caused problem with
 					html file that has three frames. _index.html file.
					Release 1.1.8
 5 Aug 2003      D. Ray Elliott     	Add support for 802.1p/q ethernet format.
					Release 1.1.9
25 Aug 2003      D. Ray Elliott     	Add basic support for kerberos tracing
26 Aug 2003      D. Ray Elliott     	Release 1.1.10
10 Sep 2003      D. Ray Elliott     	Did not work on linux/solaris perl 5.8.
13 Sep 2003      D. Ray Elliott     	Release 1.1.11
17 Nov 2003      D. Ray Elliott     	Added -summary command
17 Nov 2003      D. Ray Elliott     	Release 1.1.12
19 Nov 2003      D. Ray Elliott     	Add check for short capture lengths. and display 
					Correct error message
					Changed summary output from IPADDRESS ALIAS to IPADDRESS/ALIAS
22 Nov 2003      D. Ray Elliott     	Release 1.1.14
24 Nov 2003      D. Ray Elliott     	Added symmetric udp ports handling.
					Changed IPADDRESS[/ALIAS][:NUMBER] to
						IPADDRESS[:PORT}[/ALIAS][:NUMBER]
26 Nov 2003      D. Ray Elliott     	Fixed program to determine the version of libpcap format
					requires the use of seek
					Also improved error messages for file format errors.
26 Nov 2003      D. Ray Elliott     	Release 1.1.15
27 Nov 2003      D. Ray Elliott     	Automatcially change width between column 
					if not set by command.
29 Nov 2003      D. Ray Elliott     	added -keep option Delete generated files that are undesired.
29 Nov 2003      D. Ray Elliott     	Release 1.1.16
 3 Dec 2003      D. Ray Elliott     	Fixed  bug in symmetric udp port detection
 4 Dec 2003      D. Ray Elliott     	Added Merge capture file capability.
 					automatic time syncronization.
					automatic discarding of duplicate usp SIP messages.
 5 Dec 2003      D. Ray Elliott     	Added singleua option for symmetric udp port detection
 					by globally or by IPADDRESS
 6 Dec 2003      D. Ray Elliott     	Fixed output so that it can be a directory name without "/" afterwards.
 6 Dec 2003      D. Ray Elliott     	Fixed infinite loop bug in merge capture file 
 					when there are identical pkts in the first file.
 7 Dec 2003      D. Ray Elliott     	Added filter -exclude:expression
 8 Dec 2003      D. Ray Elliott     	Release 1.1.17
 9 Dec 2003      D. Ray Elliott     	Added Multiple expression filters -include/exclude:request
 					Change include/exclude rules. undef,include,exclude.
					Undef to include to exclude or undef to exclude.
					undef == exclude if any include else undef=include
 9 Dec 2003      D. Ray Elliott     	Added filters -include/exclude:request
 9 Dec 2003      D. Ray Elliott     	Release 1.2.0
 1 Mar 2004      D. Ray Elliott     	Added support for PPP over Ethernet
 1 Mar 2004      D. Ray Elliott     	Release 1.2.1
 5 Apr 2004      D. Ray Elliott     	Release 1.2.2 Added Errors message for merge option.
 3 May 2004      D. Ray Elliott     	Release 1.2.3 merge option - fixed duplicate packet check
 					check only for IP Src,IP Desc, IP Protocol, and Data. 
					Not time to live, etc.
23 Jun 2004      D. Ray Elliott     	Release 1.2.4 If the content contains unprintable character
					not ( \r \n \0x20 - \0x7f) then the display of the frame does
					not work properly on windows machines.
18 Jul 2004      D. Ray Elliott     	Release 1.2.5 Fixed various command syntax processing problems.
19 Jul 2004      D. Ray Elliott     	Release 1.2.6 Fixed various command syntax processing problems.
					Added disabling of pauseOnError feature.
25 Jul 2004      D. Ray Elliott     	Release 1.2.7 
					* default end time based on start time rather than time of trace.
					* Addec optional seconds to start & end time
					* changed "forced column excluding" display mechanisms.
					* Fixed various warnings.

################################################################################################
#  Restrictions: (Future Improvements)
################################################################################################
*	only handles ethernet packets.
*	does not have a gui front end,
*	does not trace non-sip packets.	Let ethereal do that or other sniffers do that.
################################################################################################
# Bug List
################################################################################################
*	Does not detected sendonly/recvonly in SDP body. (does detected hold as all port#s=0);
*	Exclude IP address requires an alias 10.10.10.10:0 does not. but 10.10.10.10/x:0 will.
################################################################################################
#  Future Improvements
################################################################################################
*	Change arrows to a graphical interface. for html page
*	-fake add feature for coping from,to headers from a previous message
*	improved help mode from command line. 
################################################################################################
';

## Symmetric UDP.  Detect symmetric udp ports. 
# assuptions 
# 1.	all SIP MSGs from the same UA are sent on the same send/receive UDP ports. 
# 2.	all SIP MSGs from all UAs are sent on UDP ports. 
# or 
# No tcp packets and no udp tx ports that are not paired
# 1) Need flag per Address/port pair. flags bits Tx Sip Msg, Tx Sip Msg 
# 2) Need flag to indicated state of Multiple UAs per IP address.
# 	a) Sip TCP msg sent/received at that IP Address.
# 	b) UDP Packet tx with no rx pacekt on that port.
my(%g_symmetric_udp_port_detection)=();
#	index by IP address is the state variable
#	index by IP address/port is the IP/port variable
my(%g_symmetric_udp_port)=();
my(%g_symmeteric_udp_port__single_port_per_ip_addr)=();

################################################################################################
################################################################################################
################################################################################################
################################################################################################
################################################################################################
#
################################################################################################
# START OF SIP_SCENARIO.PL SIP SCENARIO GENERATOR
################################################################################################

my($g_special_operations)=0;
#################################################################
# define global variables
#################################################################
#Miscelleanous globals for controlling the execution of the scenario trace. output of parsing(processing) command arguments.
my($g_progname,$g_fileFormat);
my($g_lastColor,$g_gapwidth,$g_gapwidth_cmd,$g_trace_date,$g_vertical_percent,$g_compress_mode,$g_verifyCallid,$g_expanded_mode,$g_time_mode);
my($g_gapwidth_overflow,$g_addStatistics,$g_addBlankTime,$g_addBlankQty,$g_addCallId);
my($g_addPhysicalFrameNumbers,$g_add_extra_line_on_trunc_msg_desc,$g_max_msg_desc_len);
my($g_infile,$g_outfile,$g_keep_files);

#Miscelleanous globals 
my(%g_udp_portArray,@g_udp_portArray,%g_tcp_portArray,@g_tcp_portArray,$tcp_connid_short,%g_tcpconnid);
my(@g_colorArray,$g_debug,$g_stop_processing,$g_scenario_trace,$g_scenario_trace_hdr,$g_file_frame,$g_doc_title,$g_sip_frame_number,$g_total_pkts);
my($g_date);
my($g_kerberos,%g_pKerberosApplicationDefinition,@g_kerberos_msgTypes)=(-1,(),());
my($g_cmd_params);
my(@g_delayed_args);
my($g_singleua)=(1);

## variable for storing IP address and aliases. and statistics involving IP addresses and aliases
my(%g_ip_addr_by_alias,@g_alias_by_column,@g_ip_addr_by_column,%g_alias_by_ip_addr,%g_column_by_ip_addr);

my(%g_ip_addr__not_used,%g_ip_addr__used,%g_ip_addr__n_port__used);

## statistics for filtering out packets
my($g_filtered_packets, %g_filter_cause,%g_iplist_filtered_out);

## templat blanks lines for scenario tracing
my($blankline,$blanks,$dashes);

## time handling
my($g_timePrecision);

## results of parsing IP header
my(%g_confirmed_sip_connections,%g_prevMsg);

## Global Callid Assignment
my(%g_callId,@g_callId,$g_nextCallId,%g_connectCallidShort);

## for physical Frame management
my($g_comment_prefix,%g_comment_lines,$g_fake_lines,$g_subunique_value,$g_unique_value,%g_phy_sip_pkt_list,@g_phy_sip_pkt_list,$g_phy_frame);

my($g_pStartPktInfo);
##my @g_include_expression=();
##my @g_include_expression_flag=();
my @dynamicCallFilters=();
my %dynamicCallFilters=();
##my %g_include_ip =();
##my $g_include_ip=0;
my @g_start_time=();
my $g_start_time=0;
my $g_time_arg="";
my @g_end_time=();
my $g_end_time=0;

my ($pktSortOrderFlag)="";
my ($g_sip_fragmented_pkts)=(0);

my ($g_default_callid_include_flag, @g_callid_include_list);
my ($g_pkt_include_flag, @g_pkt_include_list,%g_pkt_include_list);
my ($g_packets_added,$g_packets_deleted)=(0,0);

my ($g_rangeStart,$g_rangeLen,$g_rangeEnd)=(0,-1,0x7fffffff);

my ($g_summary_mode,$g_summary_file)=(0,"");

my ($g_pauseOnError)=(1);

## Constants from local.pm
# Set up constants
    my $SEC  = 1;
    my $MIN  = 60 * $SEC;
    my $HR   = 60 * $MIN;
    my $DAY  = 24 * $HR;
# Determine breakpoint for rolling century
    my $thisYear = (localtime())[5];
    my $nextCentury = int($thisYear / 100) * 100;
    my $breakpoint = ($thisYear + 50) % 100;
    $nextCentury += 100 if $breakpoint < 50;
my %options;
my %cheat;
my $ym;
## End Constants from local.pm


#################################################################
## Initialize various program (global) constants and variables
#################################################################
$g_keep_files=7;
$tcp_connid_short=0;
%g_tcpconnid=();
$g_default_callid_include_flag=0;
$g_pkt_include_flag=0;
@g_delayed_args=();
$g_unique_value=0;
$g_subunique_value=0;
@g_phy_sip_pkt_list=();
$g_fake_lines=0;
%g_comment_lines=();
$g_comment_prefix="";
$g_gapwidth_overflow=0;

@g_udp_portArray=();
@g_tcp_portArray=();
##html 
@g_colorArray=("Black","#FF0000","Blue","Green","Lime","Navy","Maroon","Purple","Olive","Teal");  ## Red is #FF0000

$g_fileFormat="none";
$g_nextCallId=0;
$g_timePrecision=4;		## set number of decimal digits in useconds to display

$g_add_extra_line_on_trunc_msg_desc=1;
$g_max_msg_desc_len=0;
$g_addStatistics=1;

%g_ip_addr_by_alias=();
@g_ip_addr_by_column=();
@g_alias_by_column=();
%g_alias_by_ip_addr=();
%g_column_by_ip_addr=();

%g_filter_cause=();
%g_iplist_filtered_out=();

$g_addPhysicalFrameNumbers=1;
$g_lastColor="Black";
$g_gapwidth=18;
$g_gapwidth_cmd=0;
$g_trace_date = "";
$g_total_pkts = 0;
$g_compress_mode=1;
$g_vertical_percent=33;
$g_expanded_mode=1;
$$g_verifyCallid=1;
$g_time_mode=4;
$g_infile="";
$g_outfile="";
$g_debug=0;
$g_stop_processing=0;
$g_scenario_trace="";
$g_scenario_trace_hdr="";
$g_file_frame="";
$g_doc_title="Sip Scenario Trace";
$g_addBlankTime=2;
$g_addBlankQty=0;
$g_addCallId=1;
$g_cmd_params="";
$g_sip_frame_number=0;
$g_pStartPktInfo=0;

## output file
my $stats ;
my $g_outputIndexFile=*STDOUT;
my $g_outputIndexHtmlFile=*STDOUT;
my $g_outputTextFile=$g_outputIndexFile;
my $g_outputHtmlFile=$g_outputIndexFile;

my $g_outputIndexFileName="";
my $g_outputIndexHtmlFileName="";
my $g_outputIndexHtmlFileNameBase="";
my $g_outputTextFileName="";
my $g_outputHtmlFileName="";
my $g_outputBaseName="";
my $g_outputBaseDirName="";

my ($addingCount)=0;
my (%reassemble)=();

my @asn_decode_simple_array =();

my(%tcpInfo)=();

################################################################################################################
################################################################################################################
################################################################################################################
# Start of code
################################################################################################################
################################################################################################################
################################################################################################################


# Get current date/time for display
$g_date = localtime();

## set program name in string of command arguments.
$g_progname="$0";
$g_cmd_params="$0 ";

##  Read .ini file if it exists first.
my $inifilename="sip_scenario.ini";
if ( (-f $inifilename) && (-r _) ) {
	processArg("-I$inifilename");
}

##  processes args on command line next
if ($#ARGV<0) { processArg("help"); };

my $arg;
foreach $arg (@ARGV) { processArg($arg); } ;

getBaseFileName();

initSpecialOperations();

my $port;
foreach $port (@g_udp_portArray) {
	$g_udp_portArray{$port}=1;
}
foreach $port (@g_tcp_portArray) {
	$g_tcp_portArray{$port}=1;
}

#initialize asn.1 structures for kerberos
kerberos_data_init();

&createPacketCache($g_infile,undef);
&execute_dynamic_call_filters_end_of_file();


if ($g_debug) { print STDERR "\nFinished Reading Input file\n"; }

## Generate a list of symeteric udp IP/port pairs that are used.
processes_symmetric_udp_port_information();

## Handle Exclude and reorder packets
## Execute filtering of packets
handleExcludeReorder();

if ($g_debug) { print STDERR "Finished Reordering / Excluding process\n"; }

## handle parsing of delayed execution args
while ($#g_delayed_args>=0) { processArgDelayed(shift(@g_delayed_args)); }

if ($g_debug) { print STDERR "Generate IP Address For columns\n"; }



## must generate list of real ip addresses. and order table
handle_ip_address2();

## Now that we have the real list of ip addresses and the table is ordered.
## we can display the table. 
if ($g_summary_mode) {
	handleSummaryMode();
	exit 0;
}


&generateBlankline();

if ($g_debug) { print STDERR "Start Generating SIP Scenarios\n"; }


$addingCount=0;

my $g_scenarioFilename="${g_outputBaseDirName}${g_outputBaseName}.txt";
unless (open(SCENARIO, "+>$g_scenarioFilename") ) {
	print "*** ERROR:can't open for write $g_scenarioFilename:$!\n";
	exit_rtn(-1);
};  

my $g_frameFilename="${g_outputBaseDirName}${g_outputBaseName}.html";
unless (open(FRAME, "+>$g_frameFilename") ) {
	print "*** ERROR:can't open for write $g_frameFilename:$!\n";
	exit_rtn(-1);
};  

generateScenarioDiagrams(*SCENARIO,*FRAME);

## double check for changing gap width
if (($g_gapwidth_cmd==0) && (2+$g_max_msg_desc_len>$g_gapwidth) ) {
	## reset all varaible and seek files back to start.
	## so that the current scenario diagrams are flushed out.
	$g_gapwidth_overflow=0;
	$g_gapwidth=$g_max_msg_desc_len+3;
	$g_max_msg_desc_len=0;
	$g_scenario_trace="";
	$g_scenario_trace_hdr="";
	$g_file_frame="";
	$g_sip_fragmented_pkts=0;

	seek(SCENARIO,0,0);
	seek(FRAME,0,0);

	&generateBlankline();
	generateScenarioDiagrams(*SCENARIO,*FRAME);
}

$stats = getStats();

if ($g_debug) { print STDERR "\nGenerating Output Files\n"; }

generateOutputFiles(*SCENARIO,*FRAME);

removeHtml(\$stats);
print STDERR $stats;

##  exit program
exit_rtn(0);

################################################################################################################
################################################################################################################
################################################################################################################
# END  of main
################################################################################################################
################################################################################################################
################################################################################################################
#

sub getIpPortColumnAliasBypPktInfo {
	my($pPktInfo,$src)=@_;
	my($xip,$xport)=("${src}ip","${src}port");
	my($ip,$port)=($$pPktInfo{$xip},$$pPktInfo{$xport});
	if (! defined $ip) { 
		## displayPacket($pPktInfo,"no ip <$xip>",0);
		return (undef,undef,undef,undef);};
	if (! defined $port) { 
		## displayPacket($pPktInfo,"no port <$xport>",0);
		return (undef,undef,undef,undef);};
	## print __LINE__." $ip,$port\n";
	my($col,$alias)=(undef,undef);
	$col=$g_column_by_ip_addr{"$ip:$port"};
	$alias=$g_alias_by_ip_addr{"$ip:$port"};
	if (!defined $col) {
		$col=$g_column_by_ip_addr{"$ip"};
		$alias=$g_alias_by_ip_addr{"$ip"};
	}
	#print __LINE__." $ip $port <$col> <$alias>\n";
	return ($ip,$port,$col,$alias);
}




sub addIpAddress {
	my($ip)=@_;
	my($package, $filename, $line) = caller;
	if (defined($ip)  ) {
		if (!(exists $g_alias_by_ip_addr{$ip})) {
			## print "DRE DEBUG $line $ip\n";
			parseIpAddr($ip);
		}
	}
}

sub handle_ip_address2 {
	my($index,$column,$ip,$value)=0;
	%g_ip_addr__not_used=%g_alias_by_ip_addr;
	while ( ($ip,$value) = each(%g_ip_addr__used) ) {
		## print __LINE__." DRE DEBUG used list $ip $value\n";
		if (exists $g_ip_addr__not_used{$ip} ) {
			delete $g_ip_addr__not_used{$ip};
		}
	}

	for ($index=0;$index<=$#g_ip_addr_by_column;$index++) {
		$ip = $g_ip_addr_by_column[$index];
		if ( (defined $ip) && ($ip ne "") && (!defined $g_ip_addr__used{$ip} ))   {
			## print __LINE__." DRE DEBUG not used list $ip $index\n";
			$g_ip_addr_by_column[$index]="";
			$g_alias_by_column[$index]="";
		} else {
			## print __LINE__." DRE DEBUG used list $ip $index\n";
		}
	}
	reorder_ip_addr(0);
	%g_ip_addr__not_used=();
}

## display a summary of IP address and alias
# and eventually a summary of calls.
sub handleSummaryMode {
	my ($ip,$value);
	if ( $g_summary_file =~ /\S/ ) {
		unless (open(SUMMARYFILE, ">$g_summary_file") ) {
			print "*** ERROR:can't open for write \"$g_summary_file\" - $!\n";
			exit_rtn(-1);
		};  
		while ( ($ip,$value) = each(%g_alias_by_ip_addr) ) {
			print SUMMARYFILE "$ip/$value\n";
		}
		close SUMMARYFILE;
	} else {
		while ( ($ip,$value) = each(%g_alias_by_ip_addr) ) {
			print "$ip/$value\n";
		}
	}
}

sub pktSortFunction{
	## $a,$b defined by perl sort functions.
	my ($pPktInfo_a,$pPktInfo_b);
	my ($c,$d,$e);
	sub pktSortGet {
		my ($pPktInfo,$id)=@_;
		my $rid="$pktSortOrderFlag$id";
		if (defined($$pPktInfo{$rid})) {
			return $$pPktInfo{$rid};
		}
		if (defined($$pPktInfo{$id})) {
			return $$pPktInfo{$id};
		}
		return 0;
	}
	$pPktInfo_a=$g_phy_sip_pkt_list{$a};
	$pPktInfo_b=$g_phy_sip_pkt_list{$b};
	$c=pktSortGet($pPktInfo_a,"frame");
	$d=pktSortGet($pPktInfo_b,"frame");
	$e=$c-$d; if ($e!=0) {return $e;};
	$c=pktSortGet($pPktInfo_a,"subframe");
	$d=pktSortGet($pPktInfo_b,"subframe");
	$e=$c-$d; if ($e!=0) {return $e;};
	$c=pktSortGet($pPktInfo_a,"unique");
	$d=pktSortGet($pPktInfo_b,"unique");
	$e=$c-$d; if ($e!=0) {return $e;};
	$c=pktSortGet($pPktInfo_a,"subunique");
	$d=pktSortGet($pPktInfo_b,"subunique");
	$e=$c-$d; if ($e!=0) {return $e;};
	return 0;
}

sub handleExcludeReorder {
				my $loop=0;
				my $short_temp="";
				my $change_count=0;
				my $short2="";
	print "   ---do handleExcludeReorder()---\n";
	my($phy_pkt,$pPktInfo,$pReorderPktInfo,$pReorderPkt);
	my($exclude)=0;
	$pReorderPktInfo=0;
	$pktSortOrderFlag="";
	foreach  $phy_pkt (sort pktSortFunction  (keys (%g_phy_sip_pkt_list)) ) {
		if (defined ($pPktInfo=$g_phy_sip_pkt_list{$phy_pkt}) ) {
			if (( $$pPktInfo{event} =~ /command/)) {
				if (( $$pPktInfo{event} =~ /exclude start/)) {
					if ( ( $exclude!=0) || ($pReorderPktInfo!=0)) {
						my $arg=$$pPktInfo{arg};
						print "ERROR invalid exclude range for arg $arg\n";
						exit_rtn(-1);
					}
					$exclude=1;
					delete $g_phy_sip_pkt_list{$phy_pkt};
				} elsif (( $$pPktInfo{event} =~ /exclude end/)) {
					if ($exclude==0) {
						my $arg=$$pPktInfo{arg};
						print "ERROR invalid exclude range for arg $arg\n";
						exit_rtn(-1);
					}
					$exclude=0;
					delete $g_phy_sip_pkt_list{$phy_pkt};
				} elsif (( $$pPktInfo{event} =~ /reorder start/)) {
					if ( ( $exclude!=0) || ($pReorderPktInfo!=0)) {
						my $arg=$$pPktInfo{arg};
						print "ERROR invalid Reorder range for arg $arg\n";
						exit_rtn(-1);
					}
					$pReorderPktInfo=$pPktInfo;
					$pReorderPkt=$phy_pkt;
				} elsif (( $$pPktInfo{event} =~ /reorder end/)) {
					if ($pReorderPktInfo==0) {
						my $arg=$$pPktInfo{arg};
						print "ERROR invalid Reorder range for arg $arg\n";
						exit_rtn(-1);
					}
					$pReorderPktInfo=0;
					delete $g_phy_sip_pkt_list{$phy_pkt};
					delete $g_phy_sip_pkt_list{$pReorderPkt};
				} elsif (( $$pPktInfo{event} =~ /comment/)) {
				} elsif (( $$pPktInfo{event} =~ /ProtocolInfo/)) {
				}
			} elsif ($exclude!=0) {
				## remove excluded packets
				$g_filtered_packets++;
				my $filter_str="Sip Packet Filtered by exclude";
				$g_filter_cause{"$filter_str"}++;
				delete $g_phy_sip_pkt_list{$phy_pkt};
				next;
			} else {
				my $short=$$pPktInfo{sipcallnumber};
				print "   ---short is $short---\n";
				## if there exists any include include call filter then undefined include flags mean exclude, else include
				my $include_flag=((($g_default_callid_include_flag&1)!=0)?0:1);
				# $$pPktInfo{frame}
				if ( (defined $short) && (defined $g_callid_include_list[$short]) ) {
					$loop++;
					if ($loop==1) {
						$short_temp=$short;
						$short2=$short;
					}
					$include_flag=$g_callid_include_list[$short];
					if ($short!=$short_temp) {
						$change_count++;
					}
					if ($change_count==1) {
						$short2=$short;
					}
					print "   ---short temp is $short_temp,shor2 is $short2";
					if (($short ne $short_temp) && ($short ne $short2)) {
						$include_flag=0;
					}
					print "---inclue flag $include_flag,$g_callid_include_list[$short]---\n";
					#print "change times $change_count\n";
				}
				if ($include_flag==0) {
					## remove excluded packets
					$g_filtered_packets++;
					my $filter_str="Sip Packet Filtered by callid";
					$g_filter_cause{"$filter_str"}++;
					delete $g_phy_sip_pkt_list{$phy_pkt};
					next;
				} elsif ($pReorderPktInfo!=0) {
					## reorder packets
					$$pPktInfo{reorder_frame}= $$pReorderPktInfo{reorder_dest_frame};
					$$pPktInfo{reorder_subframe}= $$pReorderPktInfo{reorder_dest_subframe};
					$$pPktInfo{reorder_unique}=  - $$pReorderPktInfo{unique};
					$$pPktInfo{reorder_subunique}=++$g_subunique_value;
				}
				## displayPacket($pPktInfo,"Scanning");
				if ( defined $g_symmetric_udp_port{"$$pPktInfo{srcip}:$$pPktInfo{srcport}"} ) {
					$g_ip_addr__used{"$$pPktInfo{srcip}:$$pPktInfo{srcport}"}=1;;
					## print __LINE__." g_ip_addr__used $$pPktInfo{srcip}:$$pPktInfo{srcport} \n";;
				} else {
					$g_ip_addr__used{$$pPktInfo{srcip}}=1;;
					## print __LINE__." g_ip_addr__used $$pPktInfo{srcip}:$$pPktInfo{srcport} \n";;
				}
				if ( defined $g_symmetric_udp_port{"$$pPktInfo{dstip}:$$pPktInfo{dstport}"} ) {
					$g_ip_addr__used{"$$pPktInfo{dstip}:$$pPktInfo{dstport}"}=1;;
					## print __LINE__." g_ip_addr__used $$pPktInfo{dstip}:$$pPktInfo{dstport}\n";;
				} else {
					$g_ip_addr__used{$$pPktInfo{dstip}}=1;;
					## print __LINE__." g_ip_addr__used $$pPktInfo{dstip}:$$pPktInfo{dstport}\n";;
				}
				##$g_ip_addr__n_port__used{"$$pPktInfo{srcip}:$$pPktInfo{srcport}"}=1;;
				##$g_ip_addr__n_port__used{"$$pPktInfo{dstip}:$$pPktInfo{dstport}"}=1;;
				##print __LINE__." g_ip_addr__used $$pPktInfo{srcip}:$$pPktInfo{srcport} $$pPktInfo{dstip}:$$pPktInfo{dstport}\n";;
			}
		} else {
		}
	}
}


sub generateOutputFiles {
	my ($scenarioFile,$frameFile)=@_;
	$g_outputTextFileName="${g_outputBaseDirName}${g_outputBaseName}.txt";
	$g_outputHtmlFileName="${g_outputBaseDirName}${g_outputBaseName}.html";
	$g_outputIndexFileName="${g_outputBaseDirName}${g_outputBaseName}_index.html";
	$g_outputIndexHtmlFileNameBase="${g_outputBaseName}_indexhtml.html";
	$g_outputIndexHtmlFileName="${g_outputBaseDirName}${g_outputIndexHtmlFileNameBase}";

	if ($g_expanded_mode==0) {
		$g_keep_files &= ~1;	## clear keep triple frame files.
	}
	if ($g_keep_files ==0) {
		$g_keep_files = 2;	## Keep single html file
	}


	my $line;

	## Generate indexhtml file
	# This file will be used to create the html file and the txt file.
	# The other files remove information from this file.
	unless (open(INDEXHTMLFILE, "+>$g_outputIndexHtmlFileName") ) {
		print "*** ERROR:can't open for write $g_outputIndexHtmlFileName:$!\n";
		exit_rtn(-1);
	};  

	print INDEXHTMLFILE &getHtmlDocHdr();
	print INDEXHTMLFILE &getHtmlTraceHdr();
	print INDEXHTMLFILE $g_scenario_trace_hdr;
	print INDEXHTMLFILE "<a name=\"STARTOFSCENARIO\">";
	print INDEXHTMLFILE &getHtmlRefSetColorByName("Black");
	
	seek($scenarioFile,0,0);
	while (<$scenarioFile>) {
		print INDEXHTMLFILE $_;
	}
	close($scenarioFile);
	print INDEXHTMLFILE &getHtmlTraceTail();
	print INDEXHTMLFILE &getHtmlRefSetColorByName("Black");

	print INDEXHTMLFILE "<a name=\"STARTOFDETAILEDFRAMES\">";
	seek($frameFile,0,0);
	while (<$frameFile>) {
		print INDEXHTMLFILE $_;
	}
	close($frameFile);
	print INDEXHTMLFILE &addHtmlHR();
	if ($g_addStatistics!=0) {
		print INDEXHTMLFILE &addHtmlEolChars(&addHtmlEscapeChars($stats));
	}
	print INDEXHTMLFILE &getHtmlDocTail();


	if (($g_keep_files&2)!=0) {
		## Generate HTML file by removing target=bottom statements from the index html file
		unless (open(HTMLFILE, ">$g_outputHtmlFileName") ) {
			print "*** ERROR:can't open for write $g_outputHtmlFileName:$!\n";
			exit_rtn(-1);
		};  
		seek(INDEXHTMLFILE,0,0);
		while (<INDEXHTMLFILE>) {
			$line=$_;
			$line =~ s/target=\"bottom\"//gi;
			print HTMLFILE $line;
		}
		close (HTMLFILE);
	}else {
		unlink($g_outputHtmlFileName);
	}


	if (($g_keep_files&4)!=0) {
		## generate the text file by removing all html statements from the indexhtml file.
		unless (open(TXTFILE, ">$g_outputTextFileName") ) {
			print "*** ERROR:can't open for write $g_outputTextFileName:$!\n";
			exit_rtn(-1);
		};  
		seek(INDEXHTMLFILE,0,0);
		while (<INDEXHTMLFILE>) {
			$line=$_;
			removeHtml(\$line);
			print TXTFILE $line;
		}
		close (TXTFILE);
	} else {
		unlink($g_outputTextFileName);
	}

	close (INDEXHTMLFILE);
	if (($g_keep_files&1)==0) {
		## delete index file and related html file
		unlink($g_outputIndexFileName, $g_outputIndexHtmlFileName);
	} else {
		## Create Index HTML file
		unless (open(INDEXFILE, ">$g_outputIndexFileName") ) {
			print "*** ERROR:can't open for write $g_outputIndexFileName:$!\n";
			exit_rtn(-1);
		};  
		print INDEXFILE 
"<html> <frameset rows=\"7%,*,$g_vertical_percent%\">
<frame name=\"top\" src=\"$g_outputIndexHtmlFileNameBase#STARTOFSCENARIOHDR\">
<frame name=\"middle\" src=\"$g_outputIndexHtmlFileNameBase#STARTOFSCENARIO\">
<frame name=\"bottom\" src=\"$g_outputIndexHtmlFileNameBase#STARTOFDETAILEDFRAMES\">
<noframes>
<body>
<p>This page uses frames, but your browser doesn't support them.</p>
</body>
</noframes>
</frameset>
</html>
";
		close (INDEXFILE);
	}
}


sub generateScenarioDiagrams {
	my ($scenarioFile,$frameFile)=@_;
	$pktSortOrderFlag="reorder_";
	my($phy_pkt,$pPktInfo,$pStartPktInfo,$pPrevPktInfo);
	$pStartPktInfo=$g_pStartPktInfo;
	$pPrevPktInfo=$g_pStartPktInfo;
	## print "\n\nStart of List\n";
	#
	$g_sip_frame_number=0;
	generateScenarioHeader();
	foreach  $phy_pkt (sort pktSortFunction  (keys (%g_phy_sip_pkt_list)) ) {
		if ($g_debug) {
			$addingCount++;
			print STDERR "\rGenerating frame $addingCount";
		}
		if (defined ($pPktInfo=$g_phy_sip_pkt_list{$phy_pkt}) ) {
			if($pStartPktInfo==0) {
				$pStartPktInfo=$pPktInfo;
				$pPrevPktInfo=$pPktInfo;
			}
			## displayPacket($pPktInfo,"test1");
			if (( $$pPktInfo{event} =~ /command/)) {
				if (( $$pPktInfo{event} =~ /comment/)) {
					## displayPacket($pPktInfo,"comment",1);
					$g_scenario_trace.=  getHtmlRefSetColorByName("Black").
						addHtmlEolChars(addHtmlEscapeChars($blankline."\n".$g_comment_lines{$$pPktInfo{comment_dest}}));
				} else {
				}
			} else {
				## displayPacket($pPktInfo,"sipPacket");
				#
				
				if ($$pPktInfo{event}=~ /sip incomplete|sip frag|invalid sip/ ) {
					$g_sip_fragmented_pkts++;
				}
				calDateTimeStamps($pPktInfo,$pStartPktInfo,$pPrevPktInfo);
				calculateDirect ($pPktInfo);
				generateMsgDesc ($pPktInfo);
				displaySipMessage($pPktInfo,"");
				$pPrevPktInfo=$pPktInfo;
			}
			## delete $g_phy_sip_pkt_list{$phy_pkt};
			print $frameFile $g_file_frame;
			print $scenarioFile $g_scenario_trace;
			$g_file_frame="";
			$g_scenario_trace="";
		}
	}
}

sub parsefilename{
	my($longname)=@_;
	my($dirname,$filename,$basename,$extname);
	## split out directory name
	if ($longname =~ /^(.*[\\\/:])([^\\\/:]*)$/ ) {
		$dirname=$1;
		$filename=$2;
	} else {
		$dirname="";
		$filename=$longname;
	}
	## split out extension name
	if ($filename =~ /^(.*)([.][^.]*)$/) {
		$basename=$1;
		$extname=$2;
	} else {
		$basename=$filename;
		$extname="";
	}
	## print "XXXX:<$dirname><$basename><$extname>\n";
	return ($dirname,$basename,$extname);
}


sub getBaseFileName {
	my ($infile_dir,$infile,$outfile_ext,$infile_ext,$outfile_dir);
	## If there is no output file name defined then use base file name of input file. in current directory
	($infile_dir,$infile,$infile_ext)=parsefilename($g_infile);

	## print "$g_outfile($infile_dir,  $infile,  $infile_ext)=parsefilename($g_infile);\n";

	$outfile_dir="";

	if ($g_outfile =~ /^\s*$/ ) {
		$g_outfile=$infile;
	} else {
		## remove extension from the output filename
		if ( (-d $g_outfile) && (!($g_outfile =~ /(\\|\/)$/)) ) {
			## if output file is a real directory then add "/" so parse filename will work.
			$g_outfile .="/";
		}
		($outfile_dir,$g_outfile,$outfile_ext)=parsefilename($g_outfile);
		## print "($outfile_dir,  $g_outfile,  $outfile_ext)=parsefilename($g_outfile);\n";
		if ($g_outfile =~ /^\s*$/ ) {
			$g_outfile=$infile;
		}
		## print "($outfile_dir,  $g_outfile,  $outfile_ext)=parsefilename($g_outfile);\n";
	}

	$g_outputBaseDirName=$outfile_dir;

	## $g_outfile=$outfile_dir.$g_outfile;
	## $g_outfile.="/$infile" if (-d $g_outfile);
	## print "OUTPUTFILE:$g_outfile\n";

	# copy to baseFile name
	$g_outputBaseName=$g_outfile;
	if ($g_outputBaseName =~/^\s*$/) {
		print "*** ERROR: html output mode requires an output file name\n";
		exit_rtn(-1);
	}
}

sub displaySipMessage {
	my($pPktInfo,$mode)=@_;
	my($seconds,$usec,$len,$len2,$pkt);
	my($line,$notFoundIp);
	my ($g_appendInfo,$g_appendHdr);

	$notFoundIp="";
	my ($ip,$port,$col,$alias)= &getIpPortColumnAliasBypPktInfo($pPktInfo,"src");
	if (!defined $col) {
		$notFoundIp=$ip;
	} else {
		($ip,$port,$col,$alias)= getIpPortColumnAliasBypPktInfo($pPktInfo,"dst");
		if (!defined $col) {
			$notFoundIp=$ip;
		}
	}

	my $sipCallIdShort=0;
	if (!defined $notFoundIp) {
		print STDERR "ERROR:Internal Error at line ".__LINE__."\n";
		exit -1;
	}


	if ( ($notFoundIp ne "" ) ) {
		if ( (exists $g_callId{$$pPktInfo{sipcallid}} )  && ($g_verifyCallid!=0) ){
			;
		} else {
			$g_filtered_packets++;
			my $filter_str="Sip Messages that should be displayed are excluded due to: -verify:0 and  $notFoundIp:0 (excluded column)";
			$g_filter_cause{"$filter_str"}++;
			$g_iplist_filtered_out{$notFoundIp}++;
			return;
		}
	}

	$g_sip_frame_number++;

	## Set color
	my $colorindex = $$pPktInfo{sipcallnumber};
	if (defined ($$pPktInfo{sipcallnumber}) ) {
		$g_scenario_trace .= getHtmlRefSetColor(int($$pPktInfo{sipcallnumber}));
	} else {
		$g_scenario_trace .= getHtmlRefSetColorByName("Black");
	}

	## generate descriptor that goes on a line.

	## remove extra spaces 
	$$pPktInfo{msgdesc}=removeSpaces($$pPktInfo{msgdesc});

	outputScenarioFormat($pPktInfo);
	addFrameInformation($pPktInfo);
}

sub calculateDirect {
	my ($pPktInfo)=@_;
	my($low_loc,$high_loc,$src_loc,$dst_loc,$direct,$error);

	my ($src_ip,$src_port,$src_col,$src_alias)= getIpPortColumnAliasBypPktInfo($pPktInfo,"src");
	my ($dst_ip,$dst_port,$dst_col,$dst_alias)= getIpPortColumnAliasBypPktInfo($pPktInfo,"dst");

	$error="";
	#print STDERR __LINE__." DEBUG $src_col $dst_col\n";
	if ( (!defined $src_col) || (!defined $dst_col) ) {
		$error=":err[$src_ip:$src_port==>$dst_ip:$dst_port]  IP_ADDR";
		if (!defined $src_col) {
			$error .= " $src_ip";
			if (!defined $dst_col) {
				$error .= " and ";
			}
		}
		if (!defined $dst_col) {
			$error .= " $dst_ip";
		}
		$error .= " excluded from columns";
		# define columns
		$src_col=1;
		$dst_col=$#g_ip_addr_by_column+1;
	} elsif ($src_col==$dst_col) {
		$error=":same_column";
		if ($src_col <= (($#g_ip_addr_by_column+1)/2) ) {
			$dst_col=$#g_ip_addr_by_column+1;
		} else {
			$src_col=1;
		}
	}
		
	## Calculate Direction of arrows.
	$high_loc=$dst_loc=$dst_col-1;
	$low_loc=$src_loc=$src_col-1;
	$direct="right";
	if ($src_loc>$dst_loc) {
		$low_loc=$dst_loc;
		$high_loc=$src_loc;
		$direct="left";
	}
	$$pPktInfo{direct}=$direct.$error;
	$$pPktInfo{lowloc}=$low_loc;
	$$pPktInfo{hiloc}=$high_loc;
	#displayPacket($pPktInfo,"calculateDirect",1);
}

sub generateMsgDesc {
	my ($pPktInfo)=@_;
	my ($msgDesc);
	my $event=$$pPktInfo{event};
	if ($$pPktInfo{direct} =~ /left/) {
		if (($$pPktInfo{event} =~ /response/)) {
			$msgDesc="$$pPktInfo{shortcontenttype} $$pPktInfo{sipresultdesc} $$pPktInfo{sipresult} F$g_sip_frame_number";
		} elsif (($$pPktInfo{event} =~ /request/)) {
			$msgDesc="$$pPktInfo{shortcontenttype} $$pPktInfo{sipmethod} F$g_sip_frame_number";
		} elsif (($$pPktInfo{event} =~ /extraprotocol (\S+)/)) {
			$msgDesc="<<< $1 F$g_sip_frame_number";
		} else {
			$msgDesc="<<< ($$pPktInfo{event}) F$g_sip_frame_number";
		}
	} else {
		if ($event =~ /response/) {
			$msgDesc="F$g_sip_frame_number $$pPktInfo{sipresult} $$pPktInfo{sipresultdesc} $$pPktInfo{shortcontenttype}";
		} elsif (($event =~ /request/)) {
			$msgDesc="F$g_sip_frame_number $$pPktInfo{sipmethod} $$pPktInfo{shortcontenttype}";
		} elsif (($$pPktInfo{event} =~ /extraprotocol (\S+)/)) {
			$msgDesc="F$g_sip_frame_number $1 >>>";
		} else {
			$msgDesc="F$g_sip_frame_number ($$pPktInfo{event}) >>>";
		}
	}
	$$pPktInfo{msgdesc}=$msgDesc;
}

sub addFrameInformation {
	my ($pPktInfo)=@_;
	## Added expanded information. detailed frame data.
	my ($line,$pf);
	my ($srcip,$dstip,$srcport,$dstport,$src_alias,$dst_alias,$col);
	if ($g_expanded_mode!=0) {
		my($eol,$msgtype);
		$g_file_frame .= getHtmlFrameHdr($g_sip_frame_number);
		$eol=addHtmlNewLine();
		$msgtype=$$pPktInfo{msgtype};
		if (!(defined $msgtype)) {$msgtype="???";};

		($srcip,$srcport,$col,$src_alias)= getIpPortColumnAliasBypPktInfo($pPktInfo,"src");
		($dstip,$dstport,$col,$dst_alias)= getIpPortColumnAliasBypPktInfo($pPktInfo,"dst");

		$src_alias ="" if (!defined $src_alias);
		$dst_alias ="" if (!defined $dst_alias);
		
		$g_file_frame.=  sprintf("\n%16s %-8d %s:%d(%s) -> %s:%d(%s)${eol}",
			"$msgtype MESSAGE",
			$g_sip_frame_number,
			$srcip,$srcport, $src_alias,
			$dstip,$dstport,$dst_alias);
		$pf=$$pPktInfo{subframe};
		$g_file_frame.=  sprintf( "%8s Frame %-8s %s %s TimeFromPreviousSipFrame=%s TimeFromStart=%s ${eol}",
			$$pPktInfo{transport},frameStr($pPktInfo),$$pPktInfo{time_datestamp},$$pPktInfo{time_timestamp} ,$$pPktInfo{time_diff},$$pPktInfo{time_rel});
		$line= $$pPktInfo{displayinfo};
		if ($line ne "") {
			$line .=" \n";
		}
		$g_file_frame.=  addHtmlEolChars(addHtmlEscapeChars($line));
		my $tmpLine=addHtmlEolChars(addHtmlEscapeChars($$pPktInfo{sipmsg}));
		## get rid of non-displayable characters in tmpline.
		my $tmpstart="";
		# search for non-printable or control characters
		while ($tmpLine =~ /(([^[:print:]])|([[:cntrl:]])){1,1}/) {
			$tmpLine=$';
			# convert non-printable character to [XX] format where xx is the 2 hex digits that define the character.
			$tmpstart .= $`.sprintf("[%02x]",ord($&));
			#my ($found,$before)=($&,$`);
			#$found="" if (!(defined $found) );
			#$before="" if (!(defined $before) );
			#$tmpstart .= $before.sprintf("[%02x]",ord($found));
		}
		$g_file_frame.=  $tmpstart.$tmpLine;
		$g_file_frame .= getHtmlFrameTail();
	}
}

## removes space from the from and end of a line. Converts a string of white spaces to a single space.
sub removeSpaces {
	my($str)=@_;
	$str =~ s/^\s+//g;
	$str =~ s/\s+$//g;
	$str =~ s/\s+/ /g;
	return $str;
}


##############################################################################################
## calDateTimeStamps
#	formats seconds since epoch and usec into a condensed date and time stamp
#	in the format date=DD/Mon/YY  time=HH:MM:SS.subsec
# ############################################################################################
#	subsec are based on usec and can be upto 6 digits long. ethereal displays 4 digits so the percision is 4 digits.
sub calDateTimeStamps {
	my($pPktInfo,$pStartPktInfo,$pPrevPktInfo)=@_;
	my($difftime,$time,$fmttime,$wday,$mon,$day,$relativeTime,$seconds,$usec,$year,$subsec,$year2d,$factor);
	($seconds,$usec)=split /[.]/,$$pPktInfo{time};
	## All list elements are numeric, and come straight out of the C `struct tm'. $sec, $min, and $hour are the seconds, minutes, and hours of the specified time. $mday is the day of the month, and $mon is the month itself, in the range 0..11 with 0 indicating January and 11 indicating December. $year is the number of years since 1900. That is, $year is 123 in year 2023. $wday is the day of the week, with 0 indicating Sunday and 3 indicating Wednesday. $yday is the day of the year, in the range 0..364 (or 0..365 in leap years.) $isdst is true if the specified time occurs during daylight savings time, false otherwise.
	## 
	## Note that the $year element is not simply the last two digits of the year. If you assume it is, then you create non-Y2K-compliant programs--and you wouldn't want to do that, would you?
	## 
	## The proper way to get a complete 4-digit year is simply:
	## $year += 1900;
	## And to get the last two digits of the year (e.g., '01' in 2001) do:
	## $year = sprintf("%02d", $year % 100);
	## Calculate Absolut Date time
	$fmttime=localtime($seconds);
	($wday,$mon,$day,$time,$year)=split /\s+/,$fmttime;
	$year2d=sprintf("%02d",$year%100);
	$$pPktInfo{time_datestamp} = "$day\/$mon\/$year2d";
	$$pPktInfo{time_timestamp} = &formattime("$time.$usec");

	$time=$$pPktInfo{time};
	$relativeTime=$time-$$pStartPktInfo{time};
	$difftime=$time-$$pPrevPktInfo{time};

	sub formattime {
		my($time)=@_;
		my($pref,$line);
		$pref="";
		if ($time=~ /^(\d+:\d+:)(.*)$/) {
			$pref=$1;
			$time=$2;
		}
		$line=sprintf("%.4f",$time);
		return $pref.$line;
	}
	$$pPktInfo{time_rel}= &formattime($relativeTime);
	$$pPktInfo{time_diff}= &formattime($difftime);
}



##############################################################################################
# Htmt format routines
# 
# these routines place ALL formated HTML data into the output streams when needed.
# Header/Tail for the document
# Header/Tail for the callflows (arrows)
# Header/Tail for the expanded Frames 
# Some of the functions require a sequence number so that unique references can be generated for HTML
# ############################################################################################

#Document Header
sub getHtmlDocHdrShort{
	return "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">".
	"<html><body background=\"img/background.gif\">";
}

sub getHtmlDocHdr{
	my($eol,$section1,$section2,$section3);
	$eol=addHtmlNewLine();
	$section1="$g_doc_title$eol";
	$section2= "File: $g_infile${eol}Generated: $g_date${eol}Traced on: $g_trace_date";
	$section3= "Created by:$0 version=$g_version";

	return 
	&getHtmlDocHdrShort().
	"<p align=\"left\"><font color=\"Fuchsia\" face=\"Arial\"><strong><em><big><big><big><big>".
	$section1.
	"</big></big></big></big></em></strong></font>".

	"<br><font color=\"Black\" face=\"Arial\">".
	$section2.
	"</font>".

	"<font color=\"Black\" face=\"Arial\"><small><br>".
	$section3.
	"</small> </font></p>".

	"<font color=\"Black\">\n".
	"<a name=\"STARTOFSCENARIOHDR\">";
}

#Document Tail
sub getHtmlDocTail{
	return "<HR></body></html>\n";
}


sub getHtmlTraceHdr{
	"<PRE>\n";
}

sub getHtmlTraceTail{
	"</PRE>\n";
}

# The attribute value type "color" (%Color;) refers to color definitions as specified in [SRGB]. A color value may either be a hexadecimal number (prefixed by a hash mark) or one of the following sixteen color names. The color names are case-insensitive.
# 
#Black , Green , Silver , Lime , Gray , Olive , White , Yellow , Maroon , Navy , Red , Blue , Purple , Teal , Fuchsia , Aqua 
#or #RRGGBB		where RR,GG,BB are each two hex digits e.g. #FF0080
# Color names and sRGB values
# Black = "#000000"  Green = "#008000"
# Silver = "#C0C0C0"  Lime = "#00FF00"
# Gray = "#808080"  Olive = "#808000"
# White = "#FFFFFF"  Yellow = "#FFFF00"
# Maroon = "#800000"  Navy = "#000080"
# Red = "#FF0000"  Blue = "#0000FF"
# Purple = "#800080"  Teal = "#008080"
# Fuchsia = "#FF00FF"  Aqua = "#00FFFF"
#  
# Thus, the color values "#800080" and "Purple" both refer to the color purple.
# 6.5.1 Notes on using colors
# Although colors can add significant amounts of information to documents and make them more readable, please consider the following guidelines when including color in your documents:
# 
# The use of HTML elements and attributes for specifying color is deprecated. You are encouraged to use style sheets instead. 
# Don't use color combinations that cause problems for people with color blindness in its various forms. 
# If you use a background image or set the background color, then be sure to set the various text colors as well. 
# Colors specified with the BODY and FONT elements and bgcolor on tables look different on different platforms (e.g., workstations, Macs, Windows, and LCD panels vs. CRTs), so you shouldn't rely entirely on a specific effect. In the future, support for the [SRGB] color model together with ICC color profiles should mitigate this problem. 
# When practical, adopt common conventions to minimize user confusion. 
my(@colorByCallid,$nextColor);
sub getHtmlRefSetColor{
	my($colorindex)=@_;
	my($color,@colorArray);
	$color=$colorByCallid[$colorindex];
	if (!defined($color)) {
		if (!defined $nextColor) {
			$nextColor=0;
		}
		$color=++$nextColor;
		$colorByCallid[$colorindex]=$color;
	}
	$colorindex=$colorByCallid[$colorindex];
	$colorindex -=1;
	$colorindex %= (1+$#g_colorArray);
	$color= $g_colorArray[$colorindex];
	if ($color =~ /^\s*$/) {
		$color="Black";
	}
	if ($color eq $g_lastColor) {
		return "";
	}
	$g_lastColor= $color;
	return "<font color=\"$g_lastColor\">";
}

sub getHtmlRefSetColorByName {
	my($color)=@_;
	$g_lastColor= $color;
	return "<font color=\"$color\">";
}

sub getHtmlRefHdr{
	if ($g_expanded_mode==0) { return "";};
	my($sequence)=@_;
	my $targetFrame="";
	$targetFrame="";
	$targetFrame="target=\"bottom\" ";
	return "<a $targetFrame href=\"#FRAME$sequence\"><font color=\"$g_lastColor\">";
}

sub getHtmlRefTail{
	if ($g_expanded_mode==0) { return "";};
	return "</a>";
}


sub getHtmlFrameHdr{
	my($sequence)=@_;
	return "<a name=\"FRAME$sequence\"><HR><PRE>";
}
sub getHtmlFrameTail{
	"</PRE>\n";
}
sub addHtmlHR{
	return "<HR>";
}
sub addHtmlEscapeChars{
	my($line)=@_;
	if (defined $line) {
		$line =~ s/&/&amp;/g;
		$line =~ s/>/&gt;/g;
		$line =~ s/</&lt;/g;
		$line =~ s/"/&quot;/g;
	}
	return $line;
}
sub addHtmlEolChars{
	my($line)=@_;
	if (defined $line) {
		$line =~ s/\r/ /g;
		$line =~ s/\n/<BR>/g;
	}
	return $line;
}
sub addHtmlNewLine {
	return "<br>";
}

sub removeHtml{
	my($pString)= @_;
	## Remove html syntax. replaces " < ...>" pairs with ""
	#     does not handle "<>" within "<>"  like "< < > >" 
	#	"< < > >" becomes ">" and not ""
	$$pString =~ s/<PRE>//gi;
	$$pString =~ s/<\/PRE>//gi;
	$$pString =~ s/<BR>/\n/gi;
	$$pString =~ s/<HR>/================================================================================\n/gi;
	$$pString =~ s/<[^<>]*>//g;
	$$pString =~ s/&gt;/>/gi;
	$$pString =~ s/&lt;/</gi;
	$$pString =~ s/&quot;/\"/gi;
	$$pString =~ s/&amp;/\&/gi;
}




#################################################################
#   generate statistics about the creation of the scenario
#################################################################
sub getStats{
	my($tmp1,$tmp2,$tmp3,$stat,$count,$alias,$ip,$key,$value);

	$stat="";
	## $stat = "\n\nCommand Parameters:$g_cmd_params\n";

	if ($g_sip_fragmented_pkts>0) {
		$stat .= "\n$g_sip_fragmented_pkts incomplete sip message(s) encountered\n";
	}

	# display  list of Ip addresses that were not on the command line but in the trace file.
	$stat .= "\n";
	$count=0;
	foreach  $key (sort keys(%g_iplist_filtered_out)) {
		$value= $g_iplist_filtered_out{$key};
		if ($count==0) {
			$stat .= "List of ip address for SIP packets that were excluded: \n";
		}
		$stat .= "$key($value)  ";
		$count++;
		if ( ($count &7)==0) { $stat .= "\n";};
	}
	if ( ($count &7)!=0) { 
		$stat .= "\n";
	}
	if ($count!=0) { $stat .= "Check Excluded IP Addresses\n\n";};

	# display  list of Ip addresses  that were not used in the scenario but a part of the command line
	$count=0;
	while (($key,$value) = each %g_ip_addr__not_used) {
		if ($count==0) {
			$stat .= "List of ip address that were not used in the scenario:\n";
		}
		$alias=$g_alias_by_ip_addr{$key};
		if ($alias ne "" ) {
			$stat .= "$key($alias)  ";
		} else {
			$stat .= "$key  ";
		}
		$count++;
		if ( ($count &7)==0) { $stat .= "\n";};
	}
	if ( ($count &7)!=0) {
		$stat .= "\n";
	}
	if ($count!=0) { $stat .= "\n";};

	# display  list of Ip addresses  that were used in the scenario
	$count=0;
	if (0) { ##DRE DEBUG
	while (($key,$value) = each %g_ip_addr__used) {
		if ($count==0) {
			$stat .= "List of ip address used in the scenario:\n";
		}
		$alias=$g_alias_by_ip_addr{$key};
		if ($alias ne "" ) {
			$stat .= "$key($alias)  ";
		} else {
			$stat .= "$key  ";
		}
		$count++;
		if ( ($count &7)==0) { $stat .= "\n";};
	}
	if ( ($count &7)!=0) {
		$stat .= "\n";
	}
	if ($count!=0) { $stat .= "\n";};
	}

	# display  list of causes for filtering packets
	## if ($g_filtered_packets!=0) 
	{
		## $g_filter_cause{"$tmp1"}++;
		$count=0;
		my @lines;
		foreach  $key (sort {$g_filter_cause{$b} <=> $g_filter_cause{$a}}  keys (%g_filter_cause)) {
			$value=$g_filter_cause{$key};
			$tmp2="[$value]";
			push @lines , sprintf("%-6s $key\n",$tmp2);
			$count++;
		};
		sub alphanumericSort {
			my $str1=$a;
			my $str2=$b;
			$str1 =~ s/\s+$//g;
			$str2 =~ s/\s+$//g;
			my ($nextnumeric,$v1,$v2,$a1,$a2,$diff,$numeric,$zero,$nine,$len1,$len2,$offset,$char1,$char2,$alpha1,$alpha2);
			my($increasing)=-1;
			while (1) {
				$str1=~/^([^\d]*)(.*)$/; $a1=$1;$str1=$2;
				$str2=~/^([^\d]*)(.*)$/; $a2=$1;$str2=$2;
				if ((!defined $a1) && (!defined $a2)) { return 0;};
				if (!defined $a1) { return -1;};
				if (!defined $a2) { return 1;};
				if ($a1 ne $a2) {
					return $a1 cmp  $a2;
				};
				$str1=~/^([\d]*)(.*)$/; $a1=$1;$str1=$2;
				$str2=~/^([\d]*)(.*)$/; $a2=$1;$str2=$2;
				if ((!defined $a1) && (!defined $a2)) { return 0;};
				if (!defined $a1) { return -1;};
				if (!defined $a2) { return 1;};
				if ($a1 != $a2) {
					if ($a1<$a2) {
						return 0-$increasing;
					} elsif ($a1>$a2) {
						return $increasing;
					} else {
						return 0;
					}
				};
				$increasing=1;
			};
			return 0;
		}
		if ($count>0) {
			$g_total_pkts += $g_packets_added;
			$g_total_pkts -= $g_packets_deleted;
			$tmp1 = ""; $tmp1=" Fake Lines:$g_fake_lines." if $g_fake_lines!=0;
			if ($g_debug !=0) {
				$tmp1 .= " Created Packets:$g_packets_added." if $g_packets_added!=0;
				$tmp1 .= " Deleted Packets:$g_packets_deleted." if $g_packets_deleted!=0;
			}
			$tmp3=$g_total_pkts-$g_filtered_packets;
			$stat .= "List of reasons $g_filtered_packets traced packets out of $g_total_pkts in scenario were not included. $tmp3 included.\n";
			$stat .= "\t$tmp1\n" if $tmp1 ne "";
			foreach  $key (sort  alphanumericSort (@lines)) {
				$stat .= $key;
			}
		}
		# finished.
	}

	if ($g_max_msg_desc_len>0) {
		$tmp1= $g_max_msg_desc_len+2;
		$g_gapwidth_overflow=1;
		$stat .= 
"Sip Message Descriptor was Truncated. An Extra line with full msg descriptor was added. Current gapwidth=$g_gapwidth. 
	Set gapwidth to $tmp1 to avoid truncation: -gap:$tmp1
	or Disable Adding of Extra line by:	-description:0
";
	}

	return $stat;
}


#################################################################
# Generate a blank line. 
#################################################################
sub generateBlankline {
	my($index,$a);
	# Create Blank lines and lines of dashes of the exact scenario width
	$blanks="";
	$dashes="";
	for ($index=0;$index<=((1+$g_gapwidth)*($#g_ip_addr_by_column));$index++) {
		$blanks.=" ";
		$dashes.="-";
	}

	## Generate a blank line with vertical bars 
	$blankline = $blanks;
	for ($index=0;$index<=($#g_ip_addr_by_column);$index++) {
		$a=$index*(1+$g_gapwidth);
		substr($blankline,$a,1)="|";
	}
}

sub generateAppendInfo {
	my ($pPktInfo)=@_;
	my($g_appendInfo,$g_appendHdr);
	$g_appendInfo ="";
	$g_appendHdr ="";
	my $buffer;

	## Add call id to arrow lines
	## $$pPktInfo{sipcallnumber};
	if ($g_addCallId!=0) {
		if ($pPktInfo!=0) {
			$buffer=($g_addCallId!=1) ?"Call#:":"";
			if ((defined $$pPktInfo{sipcallnumber}) ) {
				$g_appendInfo.=sprintf(" ${buffer}%d",$$pPktInfo{sipcallnumber});
			} else {
				## have extraprotocol
				$g_appendInfo.=sprintf(" ${buffer} ");
			}
		}
		$g_appendHdr .= "<Call>";
	}

	## Add physical frame numebr
	if ($g_addPhysicalFrameNumbers!=0) {
		if ($pPktInfo!=0) {
			my $pf=$$pPktInfo{subframe};
			if ( (defined $pf) && ($pf !=0) ) {
				$pf="$$pPktInfo{frame}.$pf";
			} else {
				$pf="$$pPktInfo{frame}";
			}
			$g_appendInfo .=sprintf(" PF:%-3s",$pf);
		}
		$g_appendHdr .= "<PFrame>";
	}

	## Add delta time information 
	if (($g_time_mode&1)!=0) {
		if ($pPktInfo!=0) {
			$g_appendInfo.=sprintf(" %-6s",$$pPktInfo{time_diff});
		}
		$g_appendHdr .= "<DeltaTime>";
	}

	## Add delta time information 
	if (($g_time_mode&2)!=0) {
		if ($pPktInfo!=0) {
			$g_appendInfo.=sprintf(" %-6s",$$pPktInfo{time_rel});
		}
		$g_appendHdr .= "<RelTime>";
	}

	## Add Date / Time stamp info
	if (($g_time_mode&8)!=0) {
		$g_appendHdr .= "<Date>";
		if ($pPktInfo!=0) {
			$g_appendInfo.=sprintf(" %s",$$pPktInfo{time_datestamp});
		}
	}

	if (($g_time_mode&4)!=0) {
		$g_appendHdr .= "<Time>";
		if ($pPktInfo!=0) {
			$g_appendInfo.=sprintf(" %s",$$pPktInfo{time_timestamp});
		}
	}

	if ( ($pPktInfo!=0) && (defined $$pPktInfo{direct}) && ($$pPktInfo{direct} =~ /err/ ) && ($g_verifyCallid!=0) ) {
		$g_appendInfo.=" ".$';
	}

	$g_appendInfo = addHtmlEscapeChars($g_appendInfo);
	$g_appendHdr = addHtmlEscapeChars($g_appendHdr);
	return ($g_appendInfo,$g_appendHdr);
}


#################################################################
## generate html and scenario formates
#################################################################
sub outputScenarioFormat {
	my ($pPktInfo)=@_;
	my($seconds,$usec,$len,$len2,$pkt)=@_;
	my($startOfLine,$availSpace,$endOfLine,$arrowline,$labelline,$clabelline,$arrowHeadChar,$copyLen);
	my($extraline,$buffer,$index,$place,$tmp,$tmp2);
	my ($g_appendInfo,$g_appendHdr);
	#      User A           Proxy          User B  
	#          |                |              | 
	#          |    INVITE F1   |              | 
	#          |--------------->|              | 
	#          |                |   INVITE F2  | 
	#          |(100 Trying) F3 |------------->| 
	#          |<---------------|              | 
	#          |                |180 Ringing F4| 
	#          | 180 Ringing F5 |<-------------| 
	#          |<---------------|              | 
	#          |                |  200 OK F6   | 
	#          |    200 OK F7   |<-------------| 
	#          |<---------------|              | 
	#          |     ACK F8     |              | 
	#          |--------------->|    ACK F9    | 
	#          |                |------------->| 
	

	generateMsgDesc($pPktInfo);

	# Calculate locations for the following 
	# first and last position of arrow line. Direction independant 
	# Start & End arrow positions. Direction dependant
	# Available space for description.
	
	#if ( ($$pPktInfo{frame}>=450) && ($$pPktInfo{frame}<=460) ){
	#my $x=0;
	#}
	
	# Start of line = low*gap + low
	$startOfLine = 1+(1+$g_gapwidth)*$$pPktInfo{lowloc};

	# available Space = (high-low)*gap + gap
	sub calAvailSpace{
		my($lowloc,$hiloc)=@_;
		return (1+$g_gapwidth)*($hiloc-($lowloc+1)) +($g_gapwidth);
	}
	$availSpace= calAvailSpace($$pPktInfo{lowloc},$$pPktInfo{hiloc});

	# end of line = start + availSpace -1;
	$endOfLine = $startOfLine + $availSpace -1;

	$arrowline=$blankline;
	$labelline=$blankline;
	$extraline="";


	#Calculate amount of char that can be copied.
	$copyLen=length($$pPktInfo{msgdesc});
	my $a=$availSpace-2;
	if ($copyLen>$a) { 
		## this line truncates the description line because it can not fit between the two arrows.
		# add an extra line here with the complete description
		## print "DEBUG XXX: $a $copyLen $$pPktInfo{direct} $$pPktInfo{lowloc} $$pPktInfo{hiloc} \n";
		my $b;
		if ($g_add_extra_line_on_trunc_msg_desc) {
			if ($g_max_msg_desc_len<=$copyLen) {
				$g_max_msg_desc_len=$copyLen;
			}
			if ($$pPktInfo{direct} =~ /left/) {
				$b=calAvailSpace(0,$$pPktInfo{hiloc})-2;
				## print "DEBUG XXXX: $a $b $copyLen $$pPktInfo{direct} $$pPktInfo{lowloc} $$pPktInfo{hiloc} \n";
				if ($copyLen>$b) {
					$b=calAvailSpace(0,$#g_ip_addr_by_column)-2;
					if ($copyLen>$b) {
						#description does not fit between left and right vertical lines
						#Do nothing for now
						## print "DEBUG NO TODO $a $b $copyLen $$pPktInfo{direct} $$pPktInfo{lowloc} $$pPktInfo{hiloc} \n";
						$extraline=$blankline;
						substr($extraline,1,$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
					} else {
						#description fits between left and right vertical lines
						$extraline=$blankline;
						## substr($extraline,-($copyLen+2),$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
						substr($extraline,1,$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
						## print "DEBUG2:$extraline\n";
					}
				} else {
					## description fits past right arrow to left vertical line so add an extra line
					$extraline=$blankline;
					substr($extraline,$endOfLine-$copyLen,$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
					## 	print "DEBUG7:$extraline\n";
				}
			} else {
				$b=calAvailSpace($$pPktInfo{lowloc},$#g_ip_addr_by_column)-2;
				## print "DEBUG ZZZZ: $a $b $copyLen $$pPktInfo{direct} $$pPktInfo{lowloc} $$pPktInfo{hiloc} \n";
				if ($copyLen>$b) {
					$b=calAvailSpace(0,$#g_ip_addr_by_column)-2;
					if ($copyLen>$b) {
						#description does not fit between left and right vertical lines
						#Do nothing for now
						## print "DEBUG3 NO TODO $a $b $copyLen $$pPktInfo{direct} $$pPktInfo{lowloc} $$pPktInfo{hiloc} \n";
						$extraline=$blankline;
						substr($extraline,1,$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
					} else {
						#description fits between left and right vertical lines
						$extraline=$blankline;
						substr($extraline,-($copyLen+1),$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
						## substr($extraline,1,$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
						## print "DEBUG4:$extraline\n";
					}
				} else {
					## description fits past left arrow to right vertical line so add an extra line
					$extraline=$blankline;
					substr($extraline,$startOfLine+1,$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
					## print "DEBUG6:$extraline\n";
				}
			}
		}
		$copyLen=$a; 
	}

	## add dashes for arrow line   and arrow with label
	substr($arrowline,$startOfLine,$availSpace)= substr($dashes,$startOfLine,$availSpace);
	my $addLableLine=\$labelline;
	if (($g_compress_mode&2)==0) {
		$addLableLine=\$arrowline;
	}

	## displayPacket($pPktInfo,"",1);

	## print "DEBUG $copyLen $$pPktInfo{direct} $$pPktInfo{lowloc} $$pPktInfo{hiloc} \n";
	## print "DEBUG $blankline\n";
	## print "DEBUG $arrowline\n";
	## my $t=length($blankline);
	## print "DEBUG $availSpace $startOfLine $endOfLine $copyLen\n";
	## copyLen msgDescriptor to correct place (start or end) in appropiate line
	if ($$pPktInfo{direct} eq "left") {
		$arrowHeadChar="<";  # set arrowHead to left arrow
		# add label to label line and to compressed label line
		substr(${$addLableLine},$endOfLine-$copyLen,$copyLen)= substr($$pPktInfo{msgdesc},-$copyLen,$copyLen);
	} elsif ($$pPktInfo{direct} eq "right") {
		$arrowHeadChar=">";  # set arrowHead to right arrow
		# add label to label line and to compressed label line
		substr(${$addLableLine},$startOfLine+1,$copyLen)= substr($$pPktInfo{msgdesc},0,$copyLen);
	} else {
		$arrowline=$blankline;
		if ($$pPktInfo{direct} =~ /err/ ) {
			## remove vertical lines for errors.
			$arrowline=$blanks;
		}
		$arrowHeadChar=" ";  # set arrowHead to right arrow
		### add label to label line and to compressed label line
		substr(${$addLableLine},$startOfLine+1,$copyLen)= substr($$pPktInfo{msgdesc},0,$copyLen);
	}

	## Add ArrowHeads and Arrow feathers  and html references.
	substr($arrowline,$endOfLine,1)= addHtmlEscapeChars($arrowHeadChar).getHtmlRefTail();
	substr($arrowline,$startOfLine,1)= getHtmlRefHdr($g_sip_frame_number).addHtmlEscapeChars($arrowHeadChar);
		
	($g_appendInfo,$g_appendHdr)=generateAppendInfo($pPktInfo);

	## Generate column descriptors as required

	## check if blank lines need to be inserted
	if ( ($g_addBlankQty>0) && ($g_addBlankTime>0) && ($$pPktInfo{time_diff}>=$g_addBlankTime) ) {
		for ($a=0;$a<$g_addBlankQty;$a++) {
			$g_scenario_trace .= sprintf "$blankline\n";
		}
	}

	## Add extra blank line if required.
	if (($g_compress_mode&1)!=0) {
		$g_scenario_trace .= sprintf  "$blankline\n";
	}

	if ($extraline ne "" ) {
		$g_scenario_trace .= addHtmlEscapeChars(sprintf "$extraline\n");
	}
	## add use combined description and arrow or not
	if (($g_compress_mode&2)!=0) {
		$g_scenario_trace .= sprintf  "$labelline\n";
	}
	$g_scenario_trace .= sprintf  "$arrowline $g_appendInfo\n";
}


sub generateScenarioHeader {
	my ($pPktInfo)=@_;
	my($index,$place,$tmp,$copyLen,$tmp2,$descriptorline,$descriptorline2);
	my ($g_appendInfo,$g_appendHdr);
	$descriptorline=$blanks;
	$descriptorline2=$blanks;
	for ($index=0;$index<=$#g_ip_addr_by_column;$index++) {
		$place = $index*(1+$g_gapwidth);

		$tmp=$g_ip_addr_by_column[$index];
		$copyLen=length($tmp);
		if ($copyLen>$g_gapwidth) { $copyLen=$g_gapwidth; }
		## print STDERR __LINE__." DEBUG <$descriptorline> <$tmp> $copyLen\n";
		substr($descriptorline,$place,$copyLen)= substr($tmp,0,$copyLen);

		$tmp2=$g_alias_by_column[$index];
		## print STDERR __LINE__." DEBUG $index $g_ip_addr_by_column[$index] $g_alias_by_column[$index] $tmp\n";
		if ($tmp ne $tmp2) {
			$copyLen=length($tmp2);
			if ($copyLen>$g_gapwidth) { $copyLen=$g_gapwidth; }
			substr($descriptorline2,$place,$copyLen)= substr($tmp2,0,$copyLen);
		}
	}
	$g_scenario_trace_hdr .= sprintf "$descriptorline2\n";
	$g_scenario_trace_hdr .= sprintf "$descriptorline\n";
	($g_appendInfo,$g_appendHdr)=generateAppendInfo(0);
	$g_scenario_trace_hdr .= sprintf "$blankline $g_appendHdr\n";
}


## validated arg by value 
sub validateArgByValue {
	my($arg,$value,@list)=@_;
	my($index);
	$arg =~ tr/A-Z/a-z/;
	for($index=0;$index<=$#list;$index++) {
		if ($value eq $list[$index]) {
			return ;
		}
	}
	print STDERR "ERROR:Invalid value($value) for arg($arg)\n";
	exit_rtn(-1);
}

## validated value by range
sub validateArgByRange {
	my($arg,$value,@list)=@_;
	if ($value eq int($value) ) {
		if ( ($value>= $list[0]) && ($value<=$list[1]) ) {
			return;
		};
	}
	print STDERR "ERROR:Invalid value($value) for arg($arg)\n";
	exit_rtn(-1);
}

sub processArg {
	print "---start prase parament---\n";
	my($arg)=@_;
	my($startOfLine,$line,$includeFile,%arg_tmp,$ip,$alias,$rem,@args,$column,$new_alias,$len,$index);
	## print STDERR "processing arg:$arg $g_compress_mode\n";
	print "---prarment is $arg---\n";

	if ($arg =~/^\s*-{0,1}help\s*$/) {
		print "$helpstr\n";
		exit_rtn(2);
	} elsif ($arg =~/^\s*-syntax[+]{2,2}\s*$/) {
		print "$extendedParameters\n";
		exit_rtn(2);
	} elsif ($arg =~/^\s*-release:/) {
		## special commands to assist in release procedure
		## executes command with curent version.
		my $cmd=$'." $g_version";
		# print STDERR "$cmd\n";
		system "$cmd\n";
		exit_rtn(1);
	} elsif ($arg =~/^\s*-syntax\s*$/) {
		print "$syntax\n";
		exit_rtn(2);
	} elsif ($arg =~/^\s*-de(scription){0,1}\s*$/) {
		print "$description\n";
		exit_rtn(2);
	} elsif ($arg =~/^\s*-l(icense(info){0,1}){0,1}\s*$/) {
		print "$license\n";
		exit_rtn(2);
	} elsif ($arg =~/^\s*-about\s*$/) {
		print "$about\n";
		exit_rtn(2);
	}
	elsif ($arg =~ /^-o(.*)/) { if ((0) && ($g_outfile ne "")) {
		print "Output file already Defined by $g_outfile : $arg\n"; exit_rtn(-1);}; $g_outfile=$1; }
	elsif ($arg =~ /^-v(ersion){0,1}\s*$/ ) {print $g_displayVersion;exit_rtn(0); }
	elsif ($arg =~ /^-g(ap){0,1}[,:.]{0,1}(\d+)$/i ) { $g_gapwidth_cmd=1;$g_gapwidth=$2;validateArgByRange($arg,$2,(5,99)); }
	elsif ($arg =~ /^-debug[,:.]{0,1}(\d*)$/i ) { $g_debug=$1; if ( (!defined $g_debug) || ($g_debug eq "") ) {$g_debug=1;};}
	elsif ($arg =~ /^-singleua\s*$/i ) { $g_singleua=0;}
	elsif ($arg =~ /^-keep:(\d*)$/i ) { $g_keep_files=$1; validateArgByRange($arg,$g_keep_files,(1,7));}
	elsif ($arg =~ /^-summary[,:.]{0,1}(.*)$/i ) { 
		$g_summary_mode=1;
		$g_summary_file=$1; 
		$g_summary_file =~ s/^\s+//g; 
		$g_summary_file =~ s/\s+$//g; }
	elsif ($arg =~ /^-ker(beros){0,1}[,:.]{0,1}(\d*)$/i ) { 
		$g_kerberos=$2; 
		if ( (!defined $g_kerberos) || ($g_kerberos eq "") ) {
			$g_kerberos=1;
		};}
	elsif ($arg =~ /^-ver(ify){0,1}[,:.]{0,1}(\d+)$/i ) {$g_verifyCallid=$2; validateArgByRange($arg,$2,(0,1));}

	elsif ($arg =~ /^-f(ormat){0,1}:c(allid){0,1}[,:.]{0,1}(\d+)$/i ) {$g_addCallId=$3; validateArgByRange($arg,$3,(0,2));}
	elsif ($arg =~ /^-f(ormat){0,1}:t(ime){0,1}[,:.]{0,1}(\d+)$/i ) {$g_time_mode=$3; validateArgByRange($arg,$3,(0,15));}
	elsif ($arg =~ /^-f(ormat){0,1}:f(rames){0,1}[,:.]{0,1}(\d+)$/i ) {$g_expanded_mode=$3; validateArgByRange($arg,$3,(0,1));}
	elsif ($arg =~ /^-f(ormat){0,1}:p(hy){0,1}[,:.]{0,1}(\d+)$/i ) {$g_addPhysicalFrameNumbers=$3; validateArgByRange($arg,$3,(0,1));}
	elsif ($arg =~ /^-f(ormat){0,1}:v(ertical){0,1}[,:.]{0,1}(\d+)$/i ) {$g_compress_mode=$3; validateArgByRange($arg,$3,(0,3));}
	elsif ($arg =~ /^-f(ormat){0,1}:s(pacetime){0,1}[,:.]{0,1}(\d+)[,.:\/](\d+)$/i ) {
		$g_addBlankTime=$3; $g_addBlankQty=$4; validateArgByRange($arg,$g_addBlankQty,(0,30)); }

	elsif ($arg =~ /^-per(cent){0,1}[,:.]{0,1}(\d+)$/i ) {$g_vertical_percent=$2; validateArgByRange($arg,$2,(1,75));}
	elsif ($arg =~ /^-des(cription){0,1}[,:.]{0,1}(\d+)$/i ) {$g_add_extra_line_on_trunc_msg_desc=$2; validateArgByRange($arg,$2,(0,1));}
	elsif ($arg =~ /^-t(itle){0,1}[,:.]{0,1}(.*)$/i ) { $g_doc_title=$2; } 
	elsif ($arg =~ /^-stat[,:.]{0,1}(\d+)$/i ) { $g_addStatistics=$1;validateArgByRange($arg,$1,(0,1)); }
	elsif ($arg =~ /^-colors[,:.]{0,1}(.*)\s*$/i ) { @g_colorArray=split(/,/ ,$1); }
	## elsif ($arg =~ /^-ports[,:.]{0,1}(.*)\s*$/ ) { @g_udp_portArray=split(/,/ ,$1); @g_tcp_portArray=@g_udp_portArray;}
	elsif ($arg =~ /^-ports:tcp:(.*)\s*$/i ) { @g_tcp_portArray=split(/,/ ,$1); }
	elsif ($arg =~ /^-ports:udp:(.*)\s*$/i ) { @g_udp_portArray=split(/,/ ,$1); }

	elsif ($arg =~ /^-r(ange){0,1}[:,.]{0,1}(\d+.*)$/i ) {  processArgInclude(\"-include:line:$2",\$arg); }
	elsif ($arg =~ /^-re(order){0,1}/i ) { processArgReorder(\$arg); }
	elsif ($arg =~ /^-e(xclude){0,1}/i ) { processArgExclude(\$arg); }
	elsif ($arg =~ /^-i(nclude){0,1}/i ) { print "---parament is include sth,do processArgInclude()---\n";processArgInclude(\$arg); }
	elsif (substr($arg,0,1) ne "-") {
		if ($arg =~ /^(\d+[.]\d+[.]\d+[.]\d+)/ ) { 
			my $ip=$1;
			if ($arg =~ /:singleua/i) {
				$g_symmetric_udp_port_detection{$ip}="single ua for ip addr";  
				$arg =~ s/:singleua//i;
			}
			push @g_delayed_args,$arg;
		} elsif ( ($arg ne "") && (-f $arg) && (-r _) ) { $g_infile=$arg;
		} else {
			print STDERR "ERROR:Invalid arg. Arg is not an ip address nor a readable file:$arg\n";
			exit_rtn(-1);
		}
	} elsif ($arg =~ /^-c(omment){0,1}:(\d+)[.]{0,1}(\d+){0,1}:(.*)$/i ) {
		my($loc,$dest,$dest1,$dest2,$comment,@comment);
		$dest1=$2;
		if (defined($3)) { $dest2=$3; } else { $dest2=0; }
		$comment=$4;
		$dest="$dest1.$dest2";
		if (!(defined($g_comment_lines{$dest}))) {
			my($pPktInfo);
			$pPktInfo=newCmdPktInfo("comment",$dest1,$dest2,\$arg);
			$$pPktInfo{comment_dest}=$dest;
			$g_comment_lines{$dest}="";
		}
		@comment=split_on_newline($comment);
		foreach $comment (@comment) {
			$g_comment_lines{$dest}.=$g_comment_prefix.$comment."\n";
		}
	} elsif ($arg =~ /^-c(ommentprefix){0,1}:([^:]*):\s*$/i ) {
		$g_comment_prefix=$2;
	} elsif ($arg =~ /^-fake:/i ) { 
		push @g_delayed_args,$arg;
	} elsif ($arg =~ /^-(no){0,1}pauseonerror/i ) { 
		if (defined($1) ) {
			## no pauseOnError
			$g_pauseOnError=0;
		} else {
			$g_pauseOnError=1;
		}
	} else {
		specialOpsArg($arg);
	};
	## print STDERR "processed arg:$arg $g_compress_mode\n";
}

sub processArgDelayed {
	my($arg)=@_;
	my($startOfLine,$line,$includeFile,%arg_tmp,$ip,$alias,$rem,@args,$column,$new_alias,$len,$index);
	## print STDERR "processing arg:$arg $g_compress_mode\n";

	if (substr($arg,0,1) ne "-") {
		if ($arg =~ /^(\d+[.]\d+[.]\d+[.]\d+)/ ) {
			## print STDERR __LINE__."  IP ADDRESS $arg\n";
			parseIpAddr ($arg);
		} else {
			print STDERR "ERROR:Invalid arg. Arg is not an ip address or a readable file:$arg\n";
			exit_rtn(-1);
		}
	} elsif ($arg =~ /^-fake:/ ) { 
		&addFakeMessage($arg);
	} elsif ($arg =~ /^-include/ ) { 
	} else {
		print STDERR "ERROR:Undefined arg:$arg\n";
		exit_rtn(-1);
	};
}

sub parseIpAddr {
	my ($arg)=@_;
	my($pure_ip,$port,$ip,$alias,$rem,$column,$new_alias,$len,$index,$newpos);
	$alias="";
	$new_alias=undef;
	$newpos=undef;
	if ($arg =~ /^(\d+[.]\d+[.]\d+[.]\d+)\s*(:(\d+)){0,1}\s*(\/([^:]*)){0,1}\s*(:(\d+)){0,1}/ ) {
		# $1 = ip
		# $3 = port (port if $3>=1024 or $5 defined or $7 defined else newpos)
		# $5 = Alias
		# $7 = newpos
		$ip=$1;
		$port=$3;
		$new_alias = removeSpaces($5) if (defined $5);
		$newpos = $7 if (defined $7);
		if ( (defined $port) && (! defined $5) && (! defined $7) && ($port<1024) ) {
			## $3 = newpos
			$newpos=$port;
			undef $port;
		}

		$pure_ip=$ip;
		$ip .=":$port" if (defined $port);

		$len=1;
		$column=0;
		$index=$column-1;

		#print STDERR __LINE__." DEBU0: @g_alias_by_column  col=$column alias=$alias ip=$ip len=$len $arg\n";
		## Check if ip exist. if so then get column and alias.
		if ( exists $g_column_by_ip_addr{$ip} ) {
			$column=$g_column_by_ip_addr{$ip};
			$alias=$g_alias_by_ip_addr{$ip};
			$index=$column-1;
		} else {
			$index=1+$#g_ip_addr_by_column;
			$column=$index+1;
			$alias="";
			$len=0;
		}

		#print STDERR __LINE__ . " ip=$ip port=$port alias=$new_alias pos=$newpos\n";
		## check if command needs to be changed. 
		# if there is an alias for an ip address(without specifify a port). which is only used as a single symmetric port 
		# Then fake this command to be for the specific port.
		# print STDERR __LINE__." DEBU0: col=$column alias=$alias newalias=$new_alias ip=$ip len=$len $arg\n";
		my $tmp_port=undef;
		if (    (!defined $port) && 				
			# (defined $new_alias) &&
			(defined ($tmp_port=$g_symmeteric_udp_port__single_port_per_ip_addr{$ip} ) ) && 
			#( (!(defined $g_alias_by_ip_addr{"$ip:$tmp_port"} ) ) ||
			#( (defined $g_alias_by_ip_addr{"$ip:$tmp_port"} ) && (($g_alias_by_ip_addr{"$ip:$tmp_port"} ne $new_alias) )  )
			#) &&
			1
			) {
			if (defined $tmp_port) {
				$ip .=":$tmp_port" ;
				$port=$tmp_port;

				my $cmd="$ip";
				#$cmd .=":$port" if defined $port;
				$cmd .="/$new_alias" if defined $new_alias;
				$cmd .=":$newpos" if defined $newpos;
				# print STDERR __LINE__." DEBU0: col=$column alias=$alias newalias=$new_alias ip=$ip len=$len $arg\n";
				#print STDERR __LINE__ . " ip=$ip port=$port alias=$new_alias pos=$newpos $cmd\n";
				parseIpAddr($cmd);
				return;
			};
		}

		# print STDERR "\n".__LINE__ . " ip=$ip port=$port alias=$new_alias pos=$newpos\n";

		if (defined $new_alias) {
			# check if new alias is ok to use.
			if  (  (exists($g_ip_addr_by_alias{$new_alias} ) ) && ($g_ip_addr_by_alias{$alias} ne $ip )  ) {
				print STDERR "Can not use the same alias twice. $alias is used by both $g_ip_addr_by_alias{$alias} and $ip\n";
				exit_rtn(-1);
			}
			$alias=$new_alias;
		}

		$new_alias="" if (! defined $new_alias) ;

		## print STDERR "DEBUD: @g_alias_by_column  col=$column alias=$alias ip=$ip len=$len $arg\n";
		## print STDERR __LINE__." DEBUD: col=$column alias=$alias ip=$ip len=$len $arg\n";
		#
		# print STDERR "\n".__LINE__." DEBUG: @g_ip_addr_by_column  col=$column alias=$alias ip=$ip len=$len $arg\n";
		if ( (defined($newpos)) && ($newpos>=0) ) {
			## remove old alias & ip addr from arrays.
			if ($index<=$#g_ip_addr_by_column) {
				splice(@g_alias_by_column,$index,1);
				splice(@g_ip_addr_by_column,$index,1);
				$len=0;
			}
			## print STDERR "DEBUG:$1,$2,$3,\n";
			$column=$newpos;
			if ($column>$#g_ip_addr_by_column ) {
				$column=1+$#g_ip_addr_by_column;
			}
			$index=$column-1;
		}

		# print STDERR "\n".__LINE__." DEBUG: @g_ip_addr_by_column  col=$column alias=$alias ip=$ip len=$len $arg\n";
		if ($column>0) {
			splice(@g_alias_by_column,$index,$len,$alias);
			splice(@g_ip_addr_by_column,$index,$len,$ip);
		}
		# print STDERR "\n".__LINE__." DEBUG: @g_ip_addr_by_column  col=$column alias=$alias ip=$ip len=$len $arg\n";
		reorder_ip_addr(0);
		# print STDERR "\n".__LINE__." DEBUG: @g_ip_addr_by_column  col=$column alias=$alias ip=$ip len=$len $arg\n";
	}
}


## must reorder columns
sub reorder_ip_addr {
	my ($debug)=@_;
	my ($index,$column,$alias,$ip);
	%g_column_by_ip_addr=();
	%g_alias_by_ip_addr=();
	%g_ip_addr_by_alias=();
	$column=0;
	#print STDERR __LINE__." ip addr by column\n"; for ($index=0;$index<=$#g_ip_addr_by_column;$index++) { $ip=$g_ip_addr_by_column[$index];if (!defined $ip) {$ip=-1;};print " $g_ip_addr_by_column[$index], "; } print "\n";
	for ($index=0;$index<=$#g_ip_addr_by_column;$index++) {
		$ip=$g_ip_addr_by_column[$index];
		## print __LINE__." DEBUGXX $ip \n";
		$alias=$g_alias_by_column[$index];
		next if (!defined $ip);
		if ($debug!=0) {
			print __LINE__." DEBUGXX $column/$index $ip $alias\n";
		}
		next if ($ip =~ /^\s*$/ );
		$column++;
		$g_ip_addr_by_alias{$alias}=$ip;
		$g_alias_by_ip_addr{$ip}=$alias;
		$g_column_by_ip_addr{$ip}=$column;
		$g_ip_addr_by_column[$column-1]=$ip;
		$g_alias_by_column[$column-1]=$alias;
	}
	$#g_alias_by_column=$column-1;
	$#g_ip_addr_by_column=$column-1;
	## print STDERR __LINE__." DEBUG maxcolumn=$column <$#g_ip_addr_by_column>\n";
	## print STDERR __LINE__." DEBUG: @g_alias_by_column  @g_ip_addr_by_column col=$column alias=$alias ip=$ip\n";
	## print STDERR __LINE__." ip addr  by alias \n";
	## while (($alias,$ip) = each %g_ip_addr_by_alias) { print STDERR __LINE__." DEBUG:$ip $alias\n"; }
	## print STDERR __LINE__." alias by ip addr\n";
	## while (($ip,$alias) = each %g_alias_by_ip_addr) { print STDERR __LINE__." DEBUG:$ip $alias\n"; }
	## print STDERR __LINE__." column by ip addr\n";
	## while (($ip,$alias) = each %g_column_by_ip_addr) { print STDERR __LINE__." DEBUG:$ip $alias\n"; }
	## print __LINE__." $#g_ip_addr_by_column: @g_ip_addr_by_column \n\n";
	# print STDERR __LINE__." ip addr by column\n"; for ($index=0;$index<=$#g_ip_addr_by_column;$index++) { $ip=$g_ip_addr_by_column[$index];if (!defined $ip) {$ip=-1;};print " $g_ip_addr_by_column[$index], "; } print "\n";
}

sub handleIncludeFile {
	my ($includeFile,$skipcnt)=@_;
	my ($line,$linecnt,@args,$startOfLine);
	if ( (-f $includeFile ) && (-r _) ) {
		unless (open(INCLUDEFILE, "<$includeFile") ) {
			print "*** ERROR:can't open for read $includeFile:$!\n";
			exit_rtn(-1);
		};
		@args=<INCLUDEFILE>;
		close(INCLUDEFILE);
		$startOfLine="";
		$linecnt=0;
		while($line=shift(@args)) {
			$linecnt++;
			next if ($linecnt<=$skipcnt);	## skip first lines as reguired.

			#truncate line after first "#" include "#"
			if ($line =~ /^\s*#/) {
				$line="";
			} elsif ($line =~ /\s+#/) {
				$line=$`;
			}

			## $line=~s/^\s+//g;  Keep spaces at start of line
			$line=~s/\s+$//g;	## remove spaces at end of line
			next if ($line =~ /^\s*$/ ); ## ignore blank lines
			next if ($line =~ /^\s*perl\s*/i ); ## ignore lines that start with perl
			next if ($line =~ /^\s*rem\s+/i ); ## ignore lines that start with rem
			next if ($line =~ /^\s*exit\s*/i ); ## ignore lines that start with exit
			next if ($line =~ /^\s*sip_scenario\b/i ); ## ignore lines that start with sip_scenario
			if ($line =~ /^\s*end-of-file\s*$/i ) { ## ignore rest of file
				@args=();	## delete rest of file
			} else {
				$line = $startOfLine.$line;
				## check for joining of lines.
				if ( $line =~ /\s*\\\s*$/) {	## if line ends with  \
					$startOfLine=$`;
					$startOfLine=~s/\s+$//g;
				} else {
					$startOfLine="";
					processArg($line);
				}
			}
		}
		if ($startOfLine ne "") {
			print "ERROR: last line include file $includeFile has a join line \"\\\" at the end of line\n";
			exit_rtn(-1);
		}
	} else {
		print STDERR "ERROR:Include file $includeFile is not a readable file:$includeFile\n";
		exit_rtn(-1);
	}
}

sub split_on_newline{
	my($line)=@_;
	my($offset,$index,@lines,$search);
	@lines=();
	$search='\\n';
	while (($offset=index($line,$search))>=0) {
		push @lines,substr($line,0,$offset);
		$line=substr($line,$offset+length($search));
	};
	push @lines,$line;
	return @lines;
};

sub processArgReorder {
	my($pArg)=@_;
	my ($elems,@arg1,@arg,$loc);
	my ($elem,$dest1,$dest2,$start1,$start2,$end1,$end2);
	my($pPktInfo);
	$$pArg=~ /-reorder\s*[,:.]{0,1}\s*/;
	@arg1=split(/:/,$');
	if (($#arg1!=1) || (!($arg1[1]=~/^(\d+)[.]{0,1}(\d+){0,1}$/)) ) {
		print STDERR "ERROR:invalid Destination. Is there a \":\"?  arg:$$pArg\n";
		exit_rtn(-1);
	} else {
		$dest1=$1;
		if (defined($2)) { $dest2=$2; } else { $dest2=0; }
	}
	@arg1=split(/,/,$arg1[0]);
	if ( $#arg1<0 ) { return; }
	foreach $elem (@arg1) {
		if ($elem =~ /^\s*(\d+)[.]{0,1}(\d+){0,1}\s*$/ ) {
			$start1=$1;
			if (defined($2)) { $start2=$2; } else { $start2=0; }
			$end1=$start1;
			$end2=$start2;
		} elsif ($elem =~ /^\s*(\d+)[.]{0,1}(\d+){0,1}\s*-\s*(\d+)[.]{0,1}(\d+){0,1}\s*$/ ) {
			$start1=$1;
			if (defined($2)) { $start2=$2; } else { $start2=0; }
			$end1=$3;
			if (defined($4)) { $end2=$4; } else { $end2=0; }
		} elsif ($elem =~ /^\s*$/ ) {
			## Ignore 
		} else {
			print STDERR "ERROR:invalid elem<$elem>. arg:$$pArg\n";
			exit_rtn(-1);
		}
		if ( ($start1==0) || ($start1>$end1) ) {
			print STDERR "ERROR:invalid arg:$$pArg\n";
			exit_rtn(-1);
		}
		$pPktInfo=newCmdPktInfo("reorder start",$start1,$start2,$pArg);
		$$pPktInfo{unique}= - $g_unique_value;
		$$pPktInfo{reorder_unique}= - $g_unique_value;
		$$pPktInfo{reorder_dest_frame}= $dest1;
		$$pPktInfo{reorder_dest_subframe}= $dest2;

		$pPktInfo=newCmdPktInfo("reorder end",$end1,$end2,$pArg);
	}
}


sub processArgInclude {
	print "   ---do processArgInclude()---\n";
	my($pArg,$pRealArg)=@_;
	my(@arg);
	my ($elem,$dest1,$dest2,$start1,$start2,$end1,$end2,$include_flag,$do_callid,%pkt_inc);
	my($pPktInfo);
	if (!defined $pRealArg) {
		$pRealArg=$pArg;
	}
	%pkt_inc=();
	$do_callid=0;
	if ($$pArg=~ /-i(nclude){0,1}:c(allid){0,1}:\s*(.*)/i ) {
		print "   ---parament is $$pArg,set include_flag=1 and do_callid=1---\n";
		$include_flag=1;
		$do_callid=1;
	} elsif ($$pArg=~ /-e(xclude){0,1}:c(allid){0,1}:\s*(.*)/i ) {
		$include_flag=0;
		$do_callid=1;
	} elsif ($$pArg=~ /-i(nclude){0,1}:l(ine){0,1}:\s*(\d+.*)$/i ) {
		$include_flag=0;
	} elsif ( ($$pArg=~ /-e(xclude){0,1}:(((!:)|(not:))*)ip:\s*/i ) ) {
		setDynamicCallFilter("ip",$',0,$2,$pArg);
		return;
	} elsif ($$pArg=~ /-i(nclude){0,1}:(((!:)|(not:))*)ip:\s*/i ) {
		setDynamicCallFilter("ip",$',1,$2,$pArg);
		return;
	} elsif ($$pArg =~ /^-i(nclude){0,1}:t(ime){0,1}:(\S+)-(\S+)$/i ) {
		my $x=$3;
		my $y=$4;
		$g_time_arg=$$pArg;
		copyTime(\@g_start_time,$x,$$pArg);
		copyTime(\@g_end_time,$y,$$pArg);
		return;
	} elsif ($$pArg =~ /^-i(nclude){0,1}:(((noic:)|(ic:)|(!:)|(not:))*)m(atch){0,1}:/i ) {
		## include command
		setDynamicCallFilter("expression",$',1,$2,$pArg);
		return;
	} elsif ($$pArg =~ /^-i(nclude){0,1}:(((noic:)|(ic:)|(!:)|(not:))*)e(xpression){0,1}:/i ) {
		print "   ---parament is $$pArg,do setDynamicCallFilter()---\n";
		setDynamicCallFilter("expression",$',1,$2,$pArg);
		return;
	} elsif ($$pArg =~ /^-e(xclude){0,1}:(((noic:)|(ic:)|(!:)|(not:))*)e(xpression){0,1}:/i ) {
		setDynamicCallFilter("expression",$',0,$2,$pArg);
		return;
	} elsif ($$pArg =~ /^-i(nclude){0,1}:(((!:)|(not:))*)req(uest){0,1}:/i ) {
		setDynamicCallFilter("request",$',1,$2,$pArg);
		return;
	} elsif ($$pArg =~ /^-e(xclude){0,1}:(((!:)|(not:))*)req(uest){0,1}:/i ) {
		setDynamicCallFilter("request",$',0,$2,$pArg);
		return;
	} elsif ($$pArg =~ /^-i(nclude){0,1}:(\d+):(.*)$/i ) { 
		handleIncludeFile($3,$2); 
		return;
	} elsif ($$pArg =~ /^-i(nclude){0,1}:{0,1}(.*)$/i ) { 
		handleIncludeFile($2,0); 
		return;
	} else {
		print STDERR "ERROR:invalid arg:$$pRealArg\n";
		exit_rtn(-1);
	}
	print "   ---$3---\n";
	@arg=split(/,/,$3);
	## print "ARGS @arg \n";
	if ( $#arg<0 ) { return; }
	#	-include:callid:LIST
	foreach $elem (@arg) {
		print "   ---elem is $elem---\n";
		if ($elem =~ /^\s*(\d+)\s*$/ ) {
			print "   ---elem is number---\n";
			$start1=$1;
			$end1=$start1;
			print "   ---start1 is $start1---\n";
			print "   ---end1 is $end1---\n";
		} elsif ($elem =~ /^\s*(\d+)\s*-\s*(\d+)\s*$/ ) {
			print "   ---elem is number-number---\n";
			$start1=$1;
			$end1=$2;
		} elsif ($elem =~ /^\s*$/ ) {
			print "   ---elem is anything---\n";
			next;
		} else {
			print STDERR "ERROR:invalid arg:$$pRealArg\n";
			exit_rtn(-1);
		}
		if ( ($start1==0) || ($start1>$end1) ) {
			print STDERR "ERROR:invalid arg:$$pRealArg\n";
			exit_rtn(-1);
		}
		if ($do_callid!=0) {
			# check for include/ exclude command
			if ($include_flag==1) { ## include
				$g_default_callid_include_flag|=1;
				print "   ---g_default_callid_include_flag=$g_default_callid_include_flag---\n";
			} else {
				$g_default_callid_include_flag|=2;
			}
			while ($start1<=$end1) {
				if ( (!defined $g_callid_include_list[$start1]) || ($include_flag==0) ) {
					$g_callid_include_list[$start1]=$include_flag;
					print "   ---$g_callid_include_list[$start1]---\n";
				}
				$start1++;
				print "   ---start1 is $start1---\n";
			}
		} else {
			# check for first include command
			if ($g_pkt_include_flag==0) {
				## check for exclude command
				if ($include_flag==0) {
					## exclude command
					$g_pkt_include_flag=2;
				} else {
					## include command
					$g_pkt_include_flag=1;
				}
			}
			$end2 = $g_pkt_include_list{$start1} ;
			if (defined $end2) {
				if ($end2>$end1) {
					$end1=$end2;
				}
			}
			$g_pkt_include_list{$start1}=$end1;
		}
	}
	if ($do_callid==0) {
		print "   ---Range include here---\n";
		## Range include here
		@g_pkt_include_list=();
		$end1=$start1=0;
		foreach $start2 (sort keys %g_pkt_include_list) {
			$end2=$g_pkt_include_list{$start2};
			if ($end1>$start2) {
				## detected overlap condition include
				## join the includes intyo a single include.
				
				## remove previous range from array.
				pop @g_pkt_include_list;
				pop @g_pkt_include_list;

				# Delete current range
				delete $g_pkt_include_list{$start2};
				if ($end1>$end2) {
					$end2=$end1;
				}
				$start2=$start1;
				$g_pkt_include_list{$start1}=$end2;
			}
			push @g_pkt_include_list,$start2;
			push @g_pkt_include_list,$end2;
			$end1=$end2;
			$start1=$start2;
		}
		$g_rangeStart=0;
		$g_rangeLen=0;
		$g_rangeEnd=0;
	}
}

sub setDynamicCallFilter {
	print "   ---do setDynamicCallFilter()---\n";
	my ($type,$data,$include,$options,$pArg)=@_;
	print "   ---param1 type is $type---\n";
	print "   ---param2 data is $data---\n";
	print "   ---param3 include is $include---\n";
	print "   ---param4 options is $options---\n";
	print "   ---param5 pArg is $pArg---\n";
	my %pFilter=();
	my $pFilter=\%pFilter;
	$$pFilter{ic}="ic";
	## print __LINE__." $type<$data> $include,$options,$$pArg \n";
	while ($options =~ /^([^:]*):/) {
		print "   ---options 1 is $1---\n";
		my $opt=$1;
		$options = $';
		if ( ($opt eq "not") || ($opt eq "!") ) {
			$$pFilter{not}=1;
		} elsif ( ($opt eq "ic") && ($type eq "expression") ) {
			$$pFilter{ic}=$opt;
		} elsif ( ($opt eq "noic") && ($type eq "expression") ) {
			$$pFilter{ic}=$opt;
		} else {
			print STDERR "ERROR:invalid option for $type <$data> arg=$$pArg\n";
		}
	}
	if ( ($type eq "request" ) && (!($data =~ /^\w+$/)) ) {
		print STDERR "ERROR:invalid SIP Request =<$data> arg=$$pArg\n";
		exit_rtn(-1);
	} elsif( ($type eq "ip" ) && (!($data =~ /^(\d+\.\d+\.\d+\.\d+)\s*$/)) ) {
		print STDERR "ERROR:invalid ip address. ip=<$data> arg=$$pArg\n";
		exit_rtn(-1);
	}

	$$pFilter{$type}=$data;
	$$pFilter{include}=$include;
	print "   ---$$pFilter{$type}---\n";
	print "   ---$$pFilter{include}---\n";
	if (defined $$pFilter{not} ) {
		## put "not" filters at the start
		unshift @dynamicCallFilters,$pFilter;
		print "   ---after unshift,pFilter is $pFilter---\n"
	} else {
		## put normal filters at the end
		push @dynamicCallFilters,$pFilter;
		print "   ---after push,pFilter is $pFilter---\n"
	}
	$g_default_callid_include_flag |= (($include==1)?1:2);
}

sub execute_dynamic_call_filters {
	my ($pPktInfo)=@_;
	my $short=$$pPktInfo{sipcallnumber};
	return if (!defined $short);
	my $pFilter;
	my $filterid=-1;
	my @sipheaders = ();
	my $headersfound =0;
	my $include;
	my $callid_include;
	my $ip;
	my $expression;
	my $match;
	my $index;
	
	$callid_include=$g_callid_include_list[$short];		## get include flag per call.
	$callid_include=-1 if (! defined $callid_include);	## if no include flag then default value to -1

	while  ($filterid<$#dynamicCallFilters) {
		last if ($callid_include==0);	## if excluded skip filtering
		$filterid++;
		$pFilter=$dynamicCallFilters[$filterid];		## get filter parameters
		$include=$$pFilter{include};				## Get include or exclude.
		next if  ( ($callid_include==1) && ($include==1) );	## If filter is include and call is included then skip
		next if ( (defined $$pFilter{not}) && (defined $dynamicCallFilters{"$short:$filterid"} ) ) ;
		$match=undef;						## default match
		if ($ip=$$pFilter{ip}) {
			$match = ( $ip eq $$pPktInfo{srcip} ) || ( $ip eq $$pPktInfo{dstip} );
		} elsif ($expression=$$pFilter{request}) {
			if ($#sipheaders<0) {
				@sipheaders = split "\r\n",$$pPktInfo{sippart};
			}
			$match = $sipheaders[0] =~ /^$expression\s+[\S\s]*sip\/\d+[.]\d+$/i;
		} elsif ($expression=$$pFilter{expression}) {
			if ($#sipheaders<0) {
				@sipheaders = split "\r\n",$$pPktInfo{sippart};
			}
			for ($index=0;$index<=$#sipheaders;$index++) {
				if ($$pFilter{ic} eq "ic") {
					$match = $sipheaders[$index] =~ /$expression/i;
				} else {
					$match = $sipheaders[$index] =~ /$expression/;
				}
				if ($match) { 
					## print __LINE__." DEBUG $expression :: $sipheaders[$index]\n";
					last;
				};
			}
		}
	       	if ($match) {
			if (defined $$pFilter{not} ) {
				$dynamicCallFilters{"$short:$filterid"}=1;
			} else {
				$g_callid_include_list[$short]= $include;
				$callid_include=$include;
			}
		}
	}
}

sub execute_dynamic_call_filters_end_of_file {
	return if ($#dynamicCallFilters<0);
	## execute only for "not" call filters
	return if (! defined ${$dynamicCallFilters[0]}{not});			## if not a "not" filter then no "not" filters left.
	my $callid;
	my $callid_include;
	my $pFilter;
	my $filterid=-1;
	my $match;
	my $include;
	for ($callid=0;$callid<=$g_nextCallId;$callid++) {
		$callid_include=$g_callid_include_list[$callid];
		$callid_include=-1 if (! defined $callid_include);	## if no include flag then default value to -1
		next if  ($callid_include==0);				## If call is excluded then do next call.
		$filterid=-1;
		while  ($filterid<$#dynamicCallFilters) {
			$filterid++;
			$pFilter=$dynamicCallFilters[$filterid];		## get filter parameters
			last if (! defined $$pFilter{not});			## if not a "not" filter then no "not" filters left.
			$include=$$pFilter{include};				## Get include or exclude.
			next if  ( ($callid_include==1) && ($include==1) );	## If filter is include and call is included then skip
			$match = $dynamicCallFilters{"${callid}:$filterid"};
			if (! defined $match) {
				$g_callid_include_list[$callid]= $include;
				last if  ($include==0);				## If call is excluded then do next call.
				$callid_include=$include;
			}
		}
	}
}

sub copyTime{
	my($pTime,$time,$pArg)=@_;
	if ($time =~ /^((((\d+)\/){0,1}(\d+)\/){0,1}(\d+)\/){0,1}(\d+):(\d+)(:(\d+)){0,1}$/ ) {
		# print __LINE__." DRE $1,$2,$3,$4,$5,$6,$7,$8,$9,$10\n";
		my $sec=0;
		$sec=$10 if defined $10;
		@{$pTime}=($4,$5,$6,$7,$8,$sec);
		if ( ($sec<0) || ($sec>=60) ) {
			print STDERR "ERROR:invalid arg. Seconds out of range $$pArg\n";
			exit_rtn(-1);
		} elsif ( ($8<0) || ($8>=60) ) {
			print STDERR "ERROR:invalid arg. minutes out of range $$pArg\n";
			exit_rtn(-1);
		} elsif ( ($7<0) || ($7>=24) ) {
			print STDERR "ERROR:invalid arg. hours out of range $$pArg\n";
			exit_rtn(-1);
		} elsif ( defined $6 ) {
			if ( ($6<1) || ($6>31) ) {
				print STDERR "ERROR:invalid arg. day out of range $$pArg\n";
				exit_rtn(-1);
			} elsif ( defined $5 ) {
				if ( ($5<1) || ($5>12) ) {
					print STDERR "ERROR:invalid arg. month out of range $$pArg\n";
					exit_rtn(-1);
				} elsif ( defined $4 ) {
					if ( ($4<=1900) || ($4>=32000) ) {
						print STDERR "ERROR:invalid arg. year out of range $$pArg\n";
						exit_rtn(-1);
					}
				}
			}
		}
	} else {
		print STDERR "ERROR:invalid arg:$$pArg\n";
		exit_rtn(-1);
	};
}
sub processArgExclude{
	my($pArg)=@_;
	my(@arg);
	my ($elem,$dest1,$dest2,$start1,$start2,$end1,$end2);
	my($pPktInfo);
	$$pArg=~ /-ex(clude){0,1}\s*[,:.]{0,1}\s*/;
	@arg=split(/,/,$');
	if ( $#arg<0 ) { return; };
	# print "arg<$arg[0]>\n";
	if ($arg[0] =~ /^[,:.]{0,1}((c(allid){0,1})|(req(uest){0,1})|(e(xpression){0,1})|(ip)):/) {
		processArgInclude($pArg);
		return;
	}
	# remove : or/and line:
	$arg[0] =~ s/^[,:.]{0,1}(line:){0,1}//;
	## Process range list for exclude.
	foreach $elem (@arg) {
		if ($elem =~ /^\s*(\d+)[.]{0,1}(\d+){0,1}\s*$/ ) {
			$start1=$1;
			if (defined($2)) { $start2=$2; } else { $start2=0; }
			$end1=$start1;
			$end2=$start2;
		} elsif ($elem =~ /^\s*(\d+)[.]{0,1}(\d+){0,1}\s*-\s*(\d+)[.]{0,1}(\d+){0,1}\s*$/ ) {
			$start1=$1;
			if (defined($2)) { $start2=$2; } else { $start2=0; }
			$end1=$3;
			if (defined($4)) { $end2=$4; } else { $end2=0; }
		} elsif ($elem =~ /^\s*$/ ) {
			## Ignore 
			next;
		} else {
			print STDERR "ERROR:invalid arg:$$pArg <$elem>\n";
			exit_rtn(-1);
		}
		if ( ($start1==0) || ($start1>$end1) ) {
			print STDERR "ERROR:invalid arg:$$pArg\n";
			exit_rtn(-1);
		}
		$pPktInfo=newCmdPktInfo("exclude start",$start1,$start2,$pArg);
		$$pPktInfo{unique}= - $g_unique_value;
		$$pPktInfo{reorder_unique}= - $g_unique_value;
		$pPktInfo=newCmdPktInfo("exclude end",$end1,$end2,$pArg);
	}
}

sub exit_rtn {
	my($value)=@_;
	my($pause);
	$pause="";
	my($package, $filename, $line) = caller;
	if ($value <0) {
		$pause="Errors Encountered.";
		$pause .= " exit called from line [$line].\n";
	} elsif ($g_gapwidth_overflow!=0) {
		$value=1;
		$pause="";
	} elsif ($value>=1) {
		$pause="";
	} else {
		$pause="";
	}
	if ( ( $value !=0 ) && ($g_pauseOnError!=0) ) {
		print STDERR "${pause}Hit Enter Key to Continue\n";
		## read line 
		<STDIN>;
	}
	exit $value;
}





















##############################################################################################
# handles the trace file.
# parses the trace file format.
# extracts traced (ethernet) packets from the file with date/time stamp and len information.
# updates a list of SIP packet 
# ############################################################################################
#  $$pPktInfo{frame}
#  $$pPktInfo{time}
#  $$pPktInfo{len}
#  $$pPktInfo{pkt}
#
#  $$pPktInfo{len}
#  $$pPktInfo{ipprotocol}
#  $$pPktInfo{srcip}
#  $$pPktInfo{dstip}
#  $$pPktInfo{ipdata_offset}
#  $$pPktInfo{ipdata_len}
#
#  $$pPktInfo{msg_offset}
#  $$pPktInfo{msg_len}
#  $$pPktInfo{srcport}
#  $$pPktInfo{dstport}
#  $$pPktInfo{transport}
#  $$pPktInfo{connectid}
#
sub createPacketCache {
	my($filename,$packet_handler)=@_;
	my($seconds,$usec,$capturelen,$framelen,$pkt,$nbytes,$magic,$major,$minor,$timezone,$filelen,$future,$linktype,$filesize,$bytesRemaining,$filehdr,$tracehdr);
	my($nlink,$ctime,$mode,$blksize,$blocks,$gid,$atime,$dev,$uid,$ino,$mtime,$rdev);

	$seconds=time;
	$usec=0;
	$g_sip_frame_number=0;

	$g_total_pkts = 0;
	$g_phy_frame=0;
	%g_iplist_filtered_out=();
	%g_filter_cause=();
	$g_filtered_packets=0;
	$g_fake_lines=0;
	%g_prevMsg=();

	if ($g_infile ne "") {
		unless (open(INFILE, "<$filename") ) {
			print "*** ERROR:can't open for read $filename:$!\n";
			exit_rtn(-1);
		};
		binmode INFILE;
	} else {
		return;	## no file name is not an error. It is a normal condition for generating manual SIP messages.
		## print STDERR "requires the input file name ($g_infile) to be a libpcap formatted File\n";
		## exit_rtn(-1);
	}

	($dev,$ino,$mode,$nlink,$uid,$gid,$rdev,$filesize, $atime,$mtime,$ctime,$blksize,$blocks) = stat($filename);
	($dev,$ino,$mode,$nlink,$uid,$gid,$rdev,$filesize, $atime,$mtime,$ctime,$blksize,$blocks) = stat($filename);
	$bytesRemaining=$filesize;
	# print STDERR "$filename size=$filesize\n";

	## Read and parse file header
	$nbytes=read INFILE,$filehdr,24;
	if ($nbytes!=24) {
		print "*** ERROR:input read error.nbytes=$nbytes. file:$filename\n";
		exit_rtn(-1);
	}
	$bytesRemaining-=24;

	## capture file types.
	## Sun Snoop:	starts with ascii:snoop

	## parser header file. Find if little or big endian formatted.
	($magic,$major,$minor,$timezone,$filelen,$future,$linktype) = unpack("VvvVVVV",$filehdr);
	if ( ($magic==0xa1b2c3d4) || ($magic==0xa1b2cd34) ) {
		## printf STDERR "Have little Endian Format\n";
		$g_fileFormat="little";
	}  else {
		($magic,$major,$minor,$timezone,$filelen,$future,$linktype) = unpack("NnnNNNN",$filehdr);
		if ( ($magic==0xa1b2c3d4) || ($magic==0xa1b2cd34) ) {
			## printf STDERR "Have Big Endian Format\n";
			$g_fileFormat="big";
		} else {
			($magic) = unpack("H8",$filehdr);
			print STDERR "
File $g_infile) must be a libpcap formatted File.
The Magic Number (the first four bytes of a libpcap file) for libpcap files 
must be either a1b2c3d4,a1b2cd34,d4c3b2a1, or 34cdb2a1
The Magic Number for file $g_infile = $magic
This file may be a compressed zip file or some other capture file format.

Ethereal can read zipped files and can read other capture file formats.
If Ethereal can read file $g_infile then save the file 
using ethereal to a different libpcap format.

";
			exit_rtn(-1);
		}
	}
	## print STDERR __LINE__.": ".unpack("H48",$filehdr) ." $magic,$major,$minor,$timezone,$filelen,$future,$linktype\n";
	my $libpcap_pkt_hdr_size=16;

	## have read the file format now read the file header.
	## need to determine valid packet formats.
	## Some release of libpcap formats add information to the captured packet header
	## sometime the extra info is 8, 12, or 4 bytes long.
	## The problem is that the version does not help.
	## The solution is to look at two successive packet hdr's and see if there is an inconsistency between the headers or in the second hdr.
	my ($location,$error,$num_verified,$startlocation,$max,$seconds1,$usec1,$capturelen1,$framelen1);
	$startlocation=24;
	$max=$libpcap_pkt_hdr_size+4*7; ## maximum $libpcap_pkt_hdr_size size. must be multiple of 4
	$num_verified=0;
	while ($libpcap_pkt_hdr_size<=$max) {
		if ($num_verified==0) {
			## have an error.
			seek INFILE,$startlocation,0;
			($seconds,$usec,$capturelen,$framelen)=read_pkt_hdr(\$libpcap_pkt_hdr_size,$filename);
			$location=$startlocation+$libpcap_pkt_hdr_size+$capturelen;
		}

		seek INFILE,$location,0;
		($seconds1,$usec1,$capturelen1,$framelen1)=read_pkt_hdr(\$libpcap_pkt_hdr_size,$filename);
		$location=$location+$libpcap_pkt_hdr_size+$capturelen1;

		#$location++;   ## use this line to trigger errors

		## check for errors conditions
		if  (	
			($seconds1<$seconds) || 
			($seconds1>($seconds+100000)) || 
			($usec>1000000) || 
			($framelen1>10000) || 
			($capturelen1>10000) || 
			($framelen1<$capturelen1) 
		    ) {
			if ($libpcap_pkt_hdr_size>=$max) {
			printf STDERR "
This file does not seem to comform to the libpcap format.

Ethereal can read zipped files and can read other capture file formats.
If Ethereal can read file $g_infile then save the file 
using ethereal to a different libpcap format.
Please send your capture file to ray.elliott\@ipc.com

";
				exit_rtn(-1);
			}
			$num_verified=0;
			$libpcap_pkt_hdr_size+=4;
		} else {
			## This hdr length is ok. continue.
			if ( (++$num_verified>5) || ($bytesRemaining<($location+$libpcap_pkt_hdr_size)) ) {
				last;
			}
			($seconds,$usec,$capturelen,$framelen)=($seconds1,$usec1,$capturelen1,$framelen1);
			
		}
	}
	## reset file ptr to where it should be at this point.
	seek INFILE,$startlocation,0;
	##
	##	
	##
	while ( ($bytesRemaining>$libpcap_pkt_hdr_size) && ($g_stop_processing==0) ) {
		## print STDERR "$filename size=$filesize bytesRemaining=$bytesRemaining\n";
		sub read_pkt_hdr {
			my ($libpcap_pkt_hdr_size,$filename)=@_;
			my $tracehdr;
			my $nbytes=read INFILE,$tracehdr,$$libpcap_pkt_hdr_size;  
			my ($seconds,$usec,$capturelen,$framelen);
			if ($nbytes!=$$libpcap_pkt_hdr_size) {
				print "*** ERROR:input read error 1 $filename:$!\n";
				exit_rtn(-1);
			}
			if ($g_fileFormat eq "little") {
				($seconds,$usec,$capturelen,$framelen) = unpack("VVVV",$tracehdr);
			} else {
				($seconds,$usec,$capturelen,$framelen) = unpack("NNNN",$tracehdr);
			}
			## print STDERR __LINE__.": ".unpack("H32",$tracehdr) ." $$libpcap_pkt_hdr_size sec=$seconds,usec=$usec,caplen=$capturelen,etherlen=$framelen \n";
			return ($seconds,$usec,$capturelen,$framelen);
	        }
		($seconds,$usec,$capturelen,$framelen)=read_pkt_hdr(\$libpcap_pkt_hdr_size,$filename);
		$bytesRemaining-=$libpcap_pkt_hdr_size;

		if ($capturelen<=$bytesRemaining) {
			## 
			$nbytes=read INFILE,$pkt,$capturelen;  
			if ($nbytes!=$capturelen) {
				print "*** ERROR:input read error 1 $filename:$!\n";
				exit_rtn(-1);
			}
			$bytesRemaining-=$capturelen;
			## my $xxx=2*$nbytes;
			## print STDERR __LINE__.": $g_phy_frame ".unpack("H$xxx",$pkt)." bytesRemaining=$bytesRemaining\n";
			if ($g_trace_date eq "") {
				$g_trace_date = localtime($seconds);
				if ($g_time_arg ne "") {
					computeTime(\@g_start_time,\$g_start_time,$seconds);
					computeTime(\@g_end_time,\$g_end_time,$g_start_time);
					# print STDERR  __LINE__. " DRE YYYYY start=$g_start_time end=$g_end_time \n";
					sub computeTime {
						my($pTimeInfo,$pDestTime,$seconds)=@_;
						my($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($seconds);
						# print  __LINE__. " my1($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) \n";
						$sec=$$pTimeInfo[5] if defined($$pTimeInfo[5]);
						$min=$$pTimeInfo[4] if defined($$pTimeInfo[4]);
						$hour=$$pTimeInfo[3] if defined($$pTimeInfo[3]);
						$mday=$$pTimeInfo[2] if defined($$pTimeInfo[2]);
						$mon=$$pTimeInfo[1]-1 if defined($$pTimeInfo[1]);
						$year=$$pTimeInfo[0] if defined($$pTimeInfo[0]);
						$$pDestTime = timelocal($sec,$min,$hour,$mday,$mon,$year);
						# print  __LINE__. " my2($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) \n";
						my $xx_date = localtime($$pDestTime);
						# print __LINE__ . " $xx_date $$pDestTime $seconds $g_trace_date\n";
					}
					if ( (($g_end_time-$g_start_time)<0) ) {
						my$xx= localtime($g_start_time);
						my$yy= localtime($g_end_time);
						print "Invalid time Arg:$g_time_arg endTimeRange=$yy < StartTimeRange=$xx\n";
						exit_rtn(-1);
					}
					if ( (($g_end_time-$seconds)<0) ) {
						my$xx= localtime($seconds);
						my$yy= localtime($g_end_time);
						print "Invalid time Arg:$g_time_arg endTimeRange=$yy < StartTraceTime=$xx\n";
						exit_rtn(-1);
					}
					if ($g_debug!=0) {
						my$xx= localtime($g_start_time);
						my$yy= localtime($g_end_time);
						print "Include Time range $xx - $yy \n";
					}
				} 
			}
			$g_total_pkts++;
			$g_phy_frame++;

			if ($g_pkt_include_flag>0) {
				if ($g_phy_frame>$g_rangeEnd) {
					my $start= shift @g_pkt_include_list;
					my $end= shift @g_pkt_include_list;
					if (defined $end) {
						$g_rangeStart=$start;
						$g_rangeEnd=$end;
					} else {
						$g_pkt_include_flag=-1;
					}
				}
			}
			#print "if ( ($g_phy_frame>=$g_rangeStart) && ($g_phy_frame<=$g_rangeEnd) ) \n";
			if ( ($g_phy_frame>=$g_rangeStart) && ($g_phy_frame<=$g_rangeEnd) ) {
				my ($tmp_a,$tmp_b)=( ($seconds-$g_start_time) , ($g_end_time-$seconds)   );
				## print __LINE__ . " $g_start_time $seconds $g_end_time  $tmp_a $tmp_b\n";
				if ( ! ( ($g_start_time != 0) && 
				        ( ($tmp_a<0) || ($tmp_b<0) )
				   )  ) {
					## create an array of all SIP messages
					my(%pkt_info,$pPktInfo,$conn);
					$pPktInfo=\%pkt_info;
					$$pPktInfo{linktype}= $linktype;
					$$pPktInfo{frame}= $g_phy_frame;
					$$pPktInfo{subframe}=0;
					$$pPktInfo{time}= sprintf("%d.%06d",$seconds,$usec);
					$$pPktInfo{seconds}= $seconds;
					$$pPktInfo{usec}= $usec;
					$$pPktInfo{len}= $capturelen;
					$$pPktInfo{framelen}= $framelen;
					$$pPktInfo{pkt}= $pkt;
					$$pPktInfo{displayinfo}="";
					if (defined $packet_handler) {
						&{$packet_handler}($pPktInfo);
					} elsif (!(&parse_frame($pPktInfo) =~ /ERROR/)) {
						my($pNextPktInfo,$pPktInfo2)=(0,0);
						$pNextPktInfo=$pPktInfo;
						while ($pNextPktInfo!=0) {
							## make time info relative from the first packet
							if ($g_pStartPktInfo==0) {
								$g_pStartPktInfo=$pPktInfo;
							}
							$pPktInfo=$pNextPktInfo;
							$pNextPktInfo=0;

							## parse_tcp_udp seems to mess up the order.
							($pPktInfo2,$pNextPktInfo) = parse_tcp_udp($pPktInfo);
							$pPktInfo=$pPktInfo2;
							if ($pPktInfo) {
								process_tcp_udp_pkt($pPktInfo);
								sub process_tcp_udp_pkt{
									my($pPktInfo)=@_;
									if (!(&isSipMessage($pPktInfo) =~ /ERROR/)) {
										## print "OK Packet $g_phy_frame $$pPktInfo{connectid}\n";
										$g_confirmed_sip_connections{$$pPktInfo{connectid}}=$pPktInfo;
										parseSipPacket($pPktInfo);
									} elsif (
										(exists $g_confirmed_sip_connections{$$pPktInfo{connectid}} ) &&
										(exists $$pPktInfo{msg_len} ) && 
										($$pPktInfo{msg_len}>=1 )
										) {
										parseSipPacket($pPktInfo);
									} elsif (
										## if either port is udp port 88 then KERBEROS protocol
										($$pPktInfo{ipprotocol}== 17) && 
										( ($$pPktInfo{srcport}==88) || ($$pPktInfo{dstport}==88) )
										) {
										handleKerberosPkt($pPktInfo);
									} else {
										## print "UDP/TCP but not SIP $g_phy_frame\n";
										$g_filtered_packets++;
										$a="Non Sip TCP/UDP Packet Filtered Out";
										$g_filter_cause{"$a"}++;
									}
								}
								$pPktInfo=0;
							} else {
							}
						}
					} else {
						## print "ETHERNET ERRORS $g_phy_frame\n";
					}
				} else {
					$g_filtered_packets++;
					my $filter_str="Time Filter";
					if ($g_debug!=0) {
						my $xx=localtime($seconds);
						$filter_str .=  ":  PacketTraceTime=".$xx;
					}
					$g_filter_cause{"$filter_str"}++;
				}
			} else {
				$g_filtered_packets++;
				my $filter_str="Physical Frame Range Filter";
				$g_filter_cause{"$filter_str"}++;
			}
		} else {
			$g_filtered_packets++;
			my $filter_str="Last Packet Incomplete";
			$g_filter_cause{"$filter_str"}++;
			$bytesRemaining=0;
			## printf STDERR "File Format Error ($capturelen<=$bytesRemaining)\n";
			## exit_rtn(-1);
		}
	}

	handleReassembly_end_of_file();
	handle_end_of_file_tcp_pktques();
	handleSipPacketIncomplete();
	close (INFILE);
}


sub addFrameToList{
	my($pPktInfo,$list)=@_;
	## displayPacket($pPktInfo,"",1);
	my($unique)=$$pPktInfo{unique};
	if (!defined($unique)) {
		$$pPktInfo{unique}= $unique=0;
	};
	if (!defined $list) {
		$list =\%g_phy_sip_pkt_list;
	}
	my $phyid = sprintf("%d.%d.%d",$$pPktInfo{frame},$$pPktInfo{subframe},$unique);
	if(defined($$list{$phyid})) {
		print "ERROR - Interal Logic Error. same id twice:$phyid\n".
		"Please send libpcap formated file \n";
		displayPacket($$list{$phyid},"ERROR:First Packet",2);
		displayPacket($$list{$phyid},"ERROR:New Packet",2);
		exit_rtn(-1);
	}
	if ($g_debug==1) {
		$addingCount++;
		print STDERR "\rSIP Packet $addingCount";
	}
	$$list{$phyid}=$pPktInfo;
	## print "OK Packet $phyid $$pPktInfo{frame} $$pPktInfo{connectid}  $$pPktInfo{srcip} $$pPktInfo{dstip}\n";
}

sub newCmdPktInfo{
	my($event,$dest1,$dest2,$pArg)=@_;
	my($pPktInfo,%pktInfo);
	$g_unique_value++;
	$pPktInfo=\%pktInfo;
	$$pPktInfo{event}="command $event";
	$$pPktInfo{reorder_frame}=$dest1;
	$$pPktInfo{reorder_subframe}=$dest2;
	$$pPktInfo{reorder_unique}=$g_unique_value;
	$$pPktInfo{frame}=$dest1;
	$$pPktInfo{subframe}=$dest2;
	$$pPktInfo{unique}=$g_unique_value;
	$$pPktInfo{arg}=$$pArg;
	addFrameToList($pPktInfo);
	return $pPktInfo;
}

# parses an ethernet packet for ethernet and IP headers.
# return ERROR if not ethernet and not IP and not fragmented
# return OK if good
# Updates the following fields
#  $$pPktInfo{len}
#  $$pPktInfo{ipprotocol}
#  $$pPktInfo{srcip}
#  $$pPktInfo{dstip}
#  $$pPktInfo{ipdata_offset}
#  $$pPktInfo{ipdata_len}
sub parse_frame {
	my($pPktInfo)=@_;
	my($etherhdr_len,$etherhdr_etherlen,$etherhdr_destmac,$etherhdr_srcmac);
	my($padlen,$offset,$ethernet,$ip,$tcp,$udp,$lena);
	my($iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, $iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, @iphdr_srcaddr, @iphdr_destaddr,$srcip,$dstip,$filter_str);
	my ($ethernet_8021pq);
	$filter_str="";
	## my($offset,$ethernet,$ip,$udp,@sip,@sipContent,$sipContentType,$msg);
	$etherhdr_len=14;
	my $libpcap_cooked_hdr_size=16;
	my ($cooked_pkt_type,$cooked_linklayer_type,$cooked_linked_layer_addr_len,$cooked_linked_layer_addr);

	if ( ($$pPktInfo{len} <$$pPktInfo{framelen})) {
		$g_filtered_packets++;
		$filter_str="Capture Len ($$pPktInfo{len}) is less than the actual ethernet packet length. Check ethernet analyzer settings.";
		$filter_str .= "=$$pPktInfo{framelen}.  \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		return "ERROR";
	}


	$offset=0;

	if ( ($$pPktInfo{linktype} == 113) ) {
		my $libpcap_cooked_hdr_size=16;
		## Unpack libpcap cooked hdr   - IP(ethernet) over ATM
		($cooked_pkt_type,$cooked_linklayer_type,$cooked_linked_layer_addr_len,$cooked_linked_layer_addr,$etherhdr_etherlen)=
			unpack("nnnH16n",substr($$pPktInfo{pkt},$offset,$libpcap_cooked_hdr_size));
		$etherhdr_len=$libpcap_cooked_hdr_size=16;

	} elsif ( ($$pPktInfo{linktype} == 1) ) {

		## Unpack standard ethernet header
		($etherhdr_destmac,$etherhdr_srcmac,$etherhdr_etherlen)=unpack("H12H12n",substr($$pPktInfo{pkt},$offset,$etherhdr_len));

		if ($etherhdr_etherlen == 0x8100) {  ## have 802.1Q format
			## must skip over 4 bytes of information. so we'll unpack the header again
			($ethernet_8021pq,$etherhdr_etherlen)
				=unpack("H8n",substr($$pPktInfo{pkt},$offset+12,(4+2)));
			$etherhdr_len +=4;
		} 
	} else {
		printf STDERR "
Linktype=$$pPktInfo{linktype} in libpcap information in $g_infile is not implemented.
The current implementation only understand ethernet medium.
Other medium can be supported. Please send your request along with the libpcap capture file to ray.elliott\@ipc.com
If your are tracing on ethernet then maybe the output format is not libpcap

";
		exit_rtn(-1);
	}

	$offset=$etherhdr_len;
	if ($etherhdr_etherlen == 0x8864) {  ## have ppp over ethernet data packet
		my ($pppoe_ver_type,$pppoe_code,$pppoe_sessid,$pppoe_len,$ppp_protocol_id);
		my $pppoe_hdr_size=8;
		($pppoe_ver_type,$pppoe_code,$pppoe_sessid,$pppoe_len,$ppp_protocol_id)
			=unpack("CCnnn",substr($$pPktInfo{pkt},$offset,$pppoe_hdr_size));
		$etherhdr_len += $pppoe_hdr_size;
		if ($ppp_protocol_id == 0x21 ) { ## ip protocol for ppp
			$etherhdr_etherlen=0x800;   ## fake ethernet protocol
		}
		$offset=$etherhdr_len;
	}

	if ($$pPktInfo{len} <$etherhdr_len) {
		$g_filtered_packets++;
		$filter_str="Short Packet Detected len=$$pPktInfo{len}. Check ethernet analyzer settings.";
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		return "ERROR";
	}


	## Validate packet information for bad formats.
	if ($etherhdr_etherlen != 0x800) { 
		$g_filtered_packets++;
		$filter_str=sprintf("Not IP packet: ethernet header length=0x%x (ip=0x800)",$etherhdr_etherlen);
		$filter_str=sprintf("IEEE802.3 Packet")				if ($etherhdr_etherlen <= 1500);
		$filter_str=sprintf("Arp Packets") 				if ($etherhdr_etherlen == 0x806);
		$filter_str=sprintf("Cisco loopback Packets") 			if ($etherhdr_etherlen == 0x9000);
		$filter_str=sprintf("DEC DNA Remote Console") 			if ($etherhdr_etherlen == 0x6002);
		$filter_str=sprintf("DEC DNA Routing") 				if ($etherhdr_etherlen == 0x6003);
		$filter_str=sprintf("DEC Local Area Transport")			if ($etherhdr_etherlen == 0x6004);
		$filter_str=sprintf("DEC Local Area Vax Cluster")		if ($etherhdr_etherlen == 0x6007);
		$filter_str=sprintf("PPP Over Ethernet - Discovery Stage")	if ($etherhdr_etherlen == 0x8863);
		$filter_str=sprintf("PPP Over Ethernet - NOT IP Packet")	if ($etherhdr_etherlen == 0x8864);
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		return "ERROR";
	};

	my($iphdr_len,$iphdr_ver)=(20,0);

	if ($$pPktInfo{len} <($etherhdr_len+$iphdr_len)) {
		$g_filtered_packets++;
		$filter_str="Short Packet Detected len=$$pPktInfo{len}. Check ethernet analyzer settings.";
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		return "ERROR";
	}

	$$pPktInfo{iphdr_offset}=$offset;
	## get basic part of IP header
	($iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, $iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, 
		$iphdr_srcaddr[0], $iphdr_srcaddr[1], $iphdr_srcaddr[2], $iphdr_srcaddr[3],
		$iphdr_destaddr[0], $iphdr_destaddr[1], $iphdr_destaddr[2], $iphdr_destaddr[3]
	)= unpack("CCnnnCCnCCCCCCCC",substr($$pPktInfo{pkt},$offset,$iphdr_len));

	## Validate ip hdr
	$iphdr_ver=int(($iphdr_len_ver>>4)&0xf);
	$iphdr_len=int($iphdr_len_ver&0xf)*4;
	if ($iphdr_ver != 0x4) {
		$g_filtered_packets++;
		$filter_str="UnSupported IP Version: ip_version=$iphdr_ver : not 4";
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		## print STDERR "WARNING:Invalid IP Header version or length\n";
		return "ERROR";
	};

	if ( $iphdr_len<20 ) { 
		$g_filtered_packets++;
		$filter_str="Invalide IP header len($iphdr_len) : too short = $iphdr_len. Check ethernet analyzer settings.";
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		return "ERROR";
	};
	$offset+=$iphdr_len;

	## Create Ip address strings.
	$srcip ="$iphdr_srcaddr[0].$iphdr_srcaddr[1].$iphdr_srcaddr[2].$iphdr_srcaddr[3]";
	$dstip ="$iphdr_destaddr[0].$iphdr_destaddr[1].$iphdr_destaddr[2].$iphdr_destaddr[3]";

	$$pPktInfo{srcip}=$srcip;
	$$pPktInfo{dstip}=$dstip;
	$$pPktInfo{iphdr_id}=$iphdr_id;
	$$pPktInfo{ipprotocol}=$iphdr_proto;

	## offset in now at the end of the ip hdr. 
	$$pPktInfo{ipdata_offset}=$offset;
	$$pPktInfo{ipdata_len}=$iphdr_pktlen-$iphdr_len;

	if ($$pPktInfo{len} <($etherhdr_len+$iphdr_len+$$pPktInfo{ipdata_len})) {
		$g_filtered_packets++;
		$filter_str="Short Frame Detected . Check ethernet analyzer settings.";
		$filter_str .=" len=$$pPktInfo{len}" if ($g_debug!=0);
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		return "ERROR";
	}

	if ((($iphdr_off)&0x3fff) != 0) {
		## detected a packet that needs the attention of the reassembler.
		# The key for reassemble is based on srcip & dstip & iphdr_id
		my $ret=&handleReassembly($pPktInfo,$iphdr_off,$srcip,$dstip,$iphdr_id);
		if (!($ret =~ /OK/ )) {
			return "ERROR";
		} else {
			$iphdr_pktlen=$$pPktInfo{len}-$etherhdr_len;
		}
	} else {
		## do nothing ignore bits
	}

	## remove padding if any from len
	$$pPktInfo{len}=$iphdr_pktlen+$etherhdr_len;

	addIpAddress("$$pPktInfo{srcip}" );
	addIpAddress("$$pPktInfo{dstip}" );

	#define	IPPROTO_IP		0		/* dummy for IP */
	#define	IPPROTO_ICMP		1		/* control message protocol */
	#define	IPPROTO_IGMP		2		/* group mgmt protocol */
	#define	IPPROTO_GGP		3		/* gateway^2 (deprecated) */
	#define	IPPROTO_TCP		6		/* tcp */
	#define	IPPROTO_EGP		8		/* exterior gateway protocol */
	#define	IPPROTO_PUP		12		/* pup */
	#define	IPPROTO_UDP		17		/* user datagram protocol */
	#define	IPPROTO_IDP		22		/* xns idp */
	#define	IPPROTO_TP		29 		/* tp-4 w/ class negotiation */
	#define	IPPROTO_EON		80		/* ISO cnlp */
	#define	IPPROTO_OSPF		89		/* OSPF version 2  */
	#define	IPPROTO_ENCAP		98		/* encapsulation header */
	return "OK";
}

sub handleReassembly_end_of_file {
	## if (defined %reassemble) 
	{
		my $fragkey;
		foreach $fragkey (keys(%reassemble)) {
			my $pPktInfo =$reassemble{$fragkey};
			if (defined $pPktInfo) {
				$g_filtered_packets++;
				my $filter_str="Incomplete Fragmented IP Packet ";
				$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
				$g_filter_cause{"$filter_str"}++;
			}
			delete $reassemble{$fragkey};
		}
	}
}

sub handleReassembly{
	## see rfc815
	my($pPktInfo,$iphdr_off,$srcip,$dstip,$iphdr_id)=@_;
	my $fragkey="frag_key:$$pPktInfo{srcip}:$$pPktInfo{dstip}:$$pPktInfo{iphdr_id}:$$pPktInfo{ipprotocol}";
	## print "frag_key:$$pPktInfo{srcip}:$$pPktInfo{dstip}:$$pPktInfo{iphdr_id}:$$pPktInfo{ipprotocol}:$iphdr_off\n";
	my $pRefPktInfo =$reassemble{$fragkey};
	if (defined $pRefPktInfo) {
		## check time difference. rfc729 says timedifference > 120 seconds to be discarded.
		if ( ($$pPktInfo{seconds} - $$pRefPktInfo{seconds} ) >= 121 ) {
			$g_filtered_packets++;
			my $filter_str="Incomplete Fragmented IP Packet Timeout";
			$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
			$g_filter_cause{"$filter_str"}++;
			delete $reassemble{$fragkey};
			## $pRefPktInfo =$reassemble{$fragkey};
			undef $pRefPktInfo;
		}
	}
	if (!defined $pRefPktInfo) {
		$pRefPktInfo = $pPktInfo;
		$reassemble{$fragkey} =$pRefPktInfo;
		$$pRefPktInfo{frag_key}=$fragkey;
		$$pRefPktInfo{frag_data}="";
		my (@hole,@holes);
		$hole[0]=0;
		$hole[1]=0xffffffff;
		push @holes,\@hole;
		$$pRefPktInfo{frag_holes}=\@holes;
		## print "\nCreated hole @hole $fragkey\n";
	}
	my $iphdr_frag_start=($iphdr_off&0x1fff)*8;
	my $iphdr_frag_end= $iphdr_frag_start+$$pPktInfo{ipdata_len}-1;
	my $iphdr_more_flag=$iphdr_off&0x2000;
	my $pHoles=$$pRefPktInfo{frag_holes};
	## print "Fragment $iphdr_frag_start $iphdr_frag_end \n";
	my $index;
	for ($index=0;$index<=$#{$pHoles};$index++) {
		my $pHole=$$pHoles[$index];
		if (( ($$pHole[1]>=$iphdr_frag_start) &&
		     ($$pHole[0]<=$iphdr_frag_end) )) {
		        ## remove hole from list
			splice @{$pHoles},$index,1 ;
			## print "Deleted hole @{$pHole}\n";
			my $copy_dst_start=$iphdr_frag_start;
			my $copy_dst_end=$iphdr_frag_end;
			if ($$pHole[0]<$iphdr_frag_start) {
				## create new hole
				my (@hole1);
				$hole1[0]=$$pHole[0];
				$hole1[1]=$iphdr_frag_start-1;
				splice @{$pHoles},$index,0,\@hole1;
				## print "Created hole @hole1\n";
				$index++;
			} else {
				$copy_dst_start=$$pHole[0];
			}
			if ( ($$pHole[1]>$iphdr_frag_end) && ($iphdr_more_flag!=0)) {
				## create new hole
				my @hole2;
				$hole2[0]=$iphdr_frag_end+1;
				$hole2[1]=$$pHole[1];
				splice @{$pHoles},$index,0,\@hole2;
				## print "Created hole @hole2\n";
				$index++;
			}
			if ($$pHole[1]<$iphdr_frag_end) {
				$copy_dst_end=$$pHole[1];
			}

			## copy data.
			my $copy_len=(($copy_dst_end-$copy_dst_start)+1);
			my $copy_start=$copy_dst_start-$iphdr_frag_start;
			## must insure that the fragmented buffer area can be spliced.
			if ( (length $$pRefPktInfo{frag_data} ) < $copy_dst_start) {
				my $fill_len=10+${copy_dst_start};
				$$pRefPktInfo{frag_data}.=sprintf("%${fill_len}s"," ");
			}
			substr ($$pRefPktInfo{frag_data}, $copy_dst_start,$copy_len,
				substr ($$pPktInfo{pkt},$$pPktInfo{ipdata_offset}+$copy_start,$copy_len) ) ;

			if ($iphdr_more_flag==0) {
				$$pRefPktInfo{ipdata_len}=$iphdr_frag_end+1;
			}
	        }
	}

	if ($pPktInfo!=$pRefPktInfo) {
		$g_filtered_packets++;
		my $filter_str="Fragmented IP Packet Joined";
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
	}

	my $retcode="MORE";
	## Check for Any holes
	# If no holes all done.
	if ($#{$pHoles} <0) {
		## No hole left
		## print "NO HOLES\n";
		# delete used space and references to "frag_??"
		## Copy data info over to new packet.
		## Update length information in new packet
		my $copy_len=$$pRefPktInfo{ipdata_len};
		substr ($$pPktInfo{pkt},$$pPktInfo{ipdata_offset},$copy_len,
			substr ($$pRefPktInfo{frag_data},0,$copy_len  ) ) ;
		$$pPktInfo{ipdata_len}=$copy_len;
		$$pPktInfo{len}=$$pPktInfo{ipdata_offset}+$copy_len;

		delete $$pRefPktInfo{frag_holes};
		delete $$pRefPktInfo{frag_key};
		delete $$pRefPktInfo{frag_data};
		delete $reassemble{$fragkey};
		$retcode="OK";
	}
	return $retcode;
}

#  Parses a packet  for udp or tcp data
#  returns ERROR if not tcp or UDP OK
#  Updates the following
#
#  $$pPktInfo{msg_offset}
#  $$pPktInfo{msg_len}
#  $$pPktInfo{srcport}
#  $$pPktInfo{dstport}
#  $$pPktInfo{transport}
#  $$pPktInfo{connectid}
sub parse_tcp_udp{
	my($pPktInfo)=@_;
	my($offset,$pNextPktInfo)=(0,0);
	if ($$pPktInfo{ipprotocol}== 6) {
		($pPktInfo,$pNextPktInfo)=parse_tcp_header($pPktInfo);
	} elsif ($$pPktInfo{ipprotocol}== 17) {
		## get UDP
		my $udphdr_len= unpack_udp_header($pPktInfo);
		sub unpack_udp_header {
			my($pPktInfo)=@_;
			my($udphdr_srcport, $udphdr_destport, $udphdr_len,$udphdr_cksum);
			my $hdr_len=8;
			($udphdr_srcport, $udphdr_destport, $udphdr_len,$udphdr_cksum)=unpack("nnnn",substr($$pPktInfo{pkt},$$pPktInfo{ipdata_offset},$hdr_len));

			my $flen= $$pPktInfo{ipdata_len}-$hdr_len;

			$$pPktInfo{msg_offset}=$$pPktInfo{ipdata_offset}+$hdr_len;
			$$pPktInfo{msg_len}= $flen;
			$$pPktInfo{srcport}=$udphdr_srcport;
			$$pPktInfo{dstport}=$udphdr_destport;
			$$pPktInfo{transport}="UDP";
			$$pPktInfo{connectid}="udp: $$pPktInfo{srcip}:$udphdr_srcport $$pPktInfo{dstip}:$udphdr_destport";
			return $udphdr_len;
		}

		if ($udphdr_len != ($$pPktInfo{ipdata_len})) { 
			$g_filtered_packets++;
			my $filter_str="Inconsistent UdpHeader Length";
			$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
			$g_filter_cause{"$filter_str"}++;
			## print STDERR "WARNING:Inconsistent UdpHeader Length Field. $udphdr_len $lena $len\n"; 
			return (0,0);
		}

		## check for port filtering
		if ($#g_udp_portArray>=0) {
			## check if a port is in the include port list. if so then accept packet
			if ( (!defined $g_udp_portArray{$$pPktInfo{srcport}}) && (!defined $g_udp_portArray{$$pPktInfo{dstport}}) ) {
				$g_filtered_packets++;
				my $filter_str="Udp Packet filter by port number";
				$g_filter_cause{"$filter_str"}++;
				$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
				return (0,0);
			}
		}
	} elsif ($$pPktInfo{ipprotocol}== 1) {
		$g_filtered_packets++;
		my $filter_str="ICMP Packets";
		$g_filter_cause{"$filter_str"}++;
		return (0,0);
	} elsif ($$pPktInfo{ipprotocol}== 2) {
		$g_filtered_packets++;
		my $filter_str="IGMP Packets";
		$g_filter_cause{"$filter_str"}++;
		return (0,0);
	} elsif ($$pPktInfo{ipprotocol}== 88) {
		$g_filtered_packets++;
		my $filter_str="Cisco EIGRP Packets.";
		$g_filter_cause{"$filter_str"}++;
		return (0,0);
	} elsif ($$pPktInfo{ipprotocol}== 89) {
		$g_filtered_packets++;
		my $filter_str="OSFP.";
		$g_filter_cause{"$filter_str"}++;
		return (0,0);
	} else {
		$g_filtered_packets++;
		my $filter_str="IP protocol ($$pPktInfo{ipprotocol}) not supported";
		$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
		$g_filter_cause{"$filter_str"}++;
		return (0,0);
	}
	if ($pPktInfo) {
		if ( ($$pPktInfo{msg_offset}+$$pPktInfo{msg_len}) > ($$pPktInfo{len}) ) {
			$g_filtered_packets++;
			my $filter_str="Wrong packet Size";
			$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
			$g_filter_cause{"$filter_str"}++;
			return (0,0);
		}
		addIpAddress($$pPktInfo{srcip} );
		addIpAddress($$pPktInfo{dstip} );
	}
	return ($pPktInfo,$pNextPktInfo);
}

sub frameStr {
	my($pPktInfo)=@_;
	if ($pPktInfo==0) {return "";};
	my $frame=$$pPktInfo{frame};
	my $sub=$$pPktInfo{sub};
	if ((!defined $sub) || ($sub==0) ) {
		return "$frame";
	} else {
		return "$frame.$sub";
	}
}

sub unpack_tcp_header {
	my($pPktInfo)=@_;
	## TCP header
	my ($flen);
	my($conn,$conns,$pStartPktInfo);
	my (@tcphdr_flags,$tcphdr_srcport, $tcphdr_destport , $tcphdr_seq , $tcphdr_ack, $tcphdr_off, $tcphdr_flags, $tcphdr_winsize, $tcphdr_cksum, $tcphdr_urgentptr);
	($tcphdr_srcport, $tcphdr_destport , $tcphdr_seq , $tcphdr_ack, $tcphdr_off, $tcphdr_flags, $tcphdr_winsize, $tcphdr_cksum, $tcphdr_urgentptr)
		=unpack("nnNNCCnnn",substr($$pPktInfo{pkt},$$pPktInfo{ipdata_offset},20));

	## check for port filtering
	if ($#g_tcp_portArray>=0) {
		## check if a port is in the include port list. if so then accept packet
		if ( (!defined $g_tcp_portArray{$tcphdr_srcport}) && (!defined $g_tcp_portArray{$tcphdr_destport}) ) {
			$g_filtered_packets++;
			my $filter_str="Tcp Packet filter by port number";
			$g_filter_cause{"$filter_str"}++;
			$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
			return 0;
		}
	}

	my $hdr_len=int($tcphdr_off>>4)*4;
	$flen= $$pPktInfo{ipdata_len}-$hdr_len;
	$$pPktInfo{msg_offset}=$$pPktInfo{ipdata_offset}+$hdr_len;
	$$pPktInfo{msg_len}= $flen;
	$$pPktInfo{tcpseq}=$tcphdr_seq;
	$$pPktInfo{srcport}=$tcphdr_srcport;
	$$pPktInfo{dstport}=$tcphdr_destport;
	$$pPktInfo{transport}="TCP";
	$$pPktInfo{connectid}=$conn="tcp: $$pPktInfo{srcip}:$tcphdr_srcport $$pPktInfo{dstip}:$tcphdr_destport";
	my $pTcpConn=$g_tcpconnid{$conn};
	if (!defined $pTcpConn ) {
		$conns=++$tcp_connid_short;
		$pStartPktInfo=$pPktInfo;
		my @tcpConn=($conns,$pStartPktInfo);
		$pTcpConn=\@tcpConn;
		$g_tcpconnid{$conn}=$pTcpConn;
	}
	($$pPktInfo{tcpconnectidshort}, $$pPktInfo{tcpstartpkt})=@{$pTcpConn};

	my($pTcpInfo);
	if (!defined $tcpInfo{$conn}) {
		$tcpInfo{$conn}=();
		$pTcpInfo=\%{$tcpInfo{$conn}};
		$$pTcpInfo{seq}=$$pPktInfo{tcpseq};
		my @xx=();
		my @xx1=();
		$$pTcpInfo{pktque}=\@xx;
		$$pTcpInfo{nextpktque}=\@xx1;
	}  else {
		$pTcpInfo=\%{$tcpInfo{$conn}};
	}

	if  ($tcphdr_flags & 2) {
		## Have the start of a new tcp session.
		releaseTcpPktQueues($pTcpInfo);
	} elsif ($$pPktInfo{tcpseq}- $$pTcpInfo{seq} > 3000) {
		## Have a large gap of seqeunce numbers. more than two IP pacekts.
		releaseTcpPktQueues($pTcpInfo);
	}
	return $pPktInfo;
	@tcphdr_flags=();
	push @tcphdr_flags,"Fin"	if (($tcphdr_flags&1)!=0);
	push @tcphdr_flags,"Syn"	if (($tcphdr_flags&2)!=0);
	push @tcphdr_flags,"Reset"	if (($tcphdr_flags&4)!=0);
	push @tcphdr_flags,"Push"	if (($tcphdr_flags&8)!=0);
	push @tcphdr_flags,"Ack"	if (($tcphdr_flags&16)!=0);
	push @tcphdr_flags,"Urgent"	if (($tcphdr_flags&32)!=0);
	push @tcphdr_flags,"Ecn-Echo"	if (($tcphdr_flags&64)!=0);
	push @tcphdr_flags,"Cwr"	if (($tcphdr_flags&128)!=0);
}



sub parse_tcp_header {
	my($pPktInfo,$term)=@_;
	my($conn)="";
	my($pNextPktInfo)=0;
	my($pTcpInfo);
	my($accepted)=0;
	if ($pPktInfo!=0) {
		if (!(exists $$pPktInfo{transport} )) {
			$pPktInfo=unpack_tcp_header($pPktInfo);
		}
	}
	if ($pPktInfo!=0) {
		$conn=$$pPktInfo{connectid};
		$pTcpInfo=\%{$tcpInfo{$conn}};
		if ($$pPktInfo{msg_len}==0) {
			displayTcpPacket($pPktInfo,"Zero2",1);
			$g_filtered_packets++;
			my $filter_str="TCP No Data";
			## $filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
			$g_filter_cause{"$filter_str"}++;
			## displayPacket($pPktInfo,"XXXX",0);
			$pPktInfo=0;
		}

		my($pktque,$offset,$hdr_len)=(0,0,0);
		$pNextPktInfo=0;
		$pktque=$$pTcpInfo{pktque};
		if (!defined $term) {
			my $pTmpPktInfo=getNextTcpPacketFromQue($pTcpInfo,0);
			if ( (defined $pTmpPktInfo) && ($pTmpPktInfo!=0) ) {
				## have a valid item on the pending que.
				if ($pPktInfo!=0) {
					## displayPacket($pNextPktInfo,"QUEE",0);
					## print "$g_phy_frame ENQUEUE      $pTcpInfo $$pPktInfo{frame} $$pTcpInfo{seq} $$pPktInfo{tcpseq} $$pPktInfo{msg_len} Bytes.\n";
					queTcpPacketOnQue ($pTcpInfo,$pPktInfo);
				}
				$pPktInfo=$pTmpPktInfo;
				## print "$g_phy_frame DEQUEUE      $pTcpInfo $$pPktInfo{frame} $$pTcpInfo{seq} $$pPktInfo{tcpseq} $$pPktInfo{msg_len} Bytes.\n";
			} else {
				$pNextPktInfo=0;
			}
		}
	}
	if ($pPktInfo!=0) {
		my $removelen=$$pTcpInfo{seq}-$$pPktInfo{tcpseq};
		if ($removelen>0) {
			if ($$pPktInfo{msg_len}>$removelen) {
				displayTcpPacket($pPktInfo,"removed $removelen",1);
				## remove data and try again
				$$pPktInfo{msg_len}-= $removelen;
				$$pPktInfo{msg_offset}+= $removelen;
				$$pPktInfo{tcpseq}+= $removelen;
				## print "$g_phy_frame REMOVED      $pTcpInfo $$pPktInfo{frame} $$pTcpInfo{seq} $$pPktInfo{tcpseq} $$pPktInfo{msg_len} Bytes. removed $removelen\n";
				## $$pPktInfo{displayinfo}.="$g_phy_frame Extra Information: Tcp removed $removelen Bytes as Duplicate.TcpSeq=$$pTcpInfo{seq} newPktSeq=$$pPktInfo{tcpseq} remain=$$pPktInfo{msg_len} tcpConn=$pTcpInfo\n"
				## displayPacket($pPktInfo,"DUPP",0);
			} else {
				## print "$g_phy_frame DISCARDED    $pTcpInfo $$pPktInfo{frame} $$pTcpInfo{seq} $$pPktInfo{tcpseq} $$pPktInfo{msg_len} Bytes.\n";
				displayTcpPacket($pPktInfo,"discarded packet $removelen<=$$pPktInfo{msg_len}",1);
				## no data valid. discard packet
				$g_filtered_packets++;
				my $filter_str="TCP Duplicate Data";
				$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
				$g_filter_cause{"$filter_str"}++;
				## displayPacket($pPktInfo,"DUPA",0);
				$accepted=1;
				$pPktInfo=0;
			}
		}
	}
	if ($pPktInfo!=0) {
		my $seq_diff=$$pPktInfo{tcpseq}-$$pTcpInfo{seq};
		if ($seq_diff>0) {
			## have a jump in data seq.
			# save pkt on next pktque
			## print "$g_phy_frame SKIPPED      $pTcpInfo $$pPktInfo{frame} $$pTcpInfo{seq} $$pPktInfo{tcpseq} $$pPktInfo{msg_len} Bytes.\n";
			queTcpPacketOnQue ($pTcpInfo,$pPktInfo);
			$pPktInfo=0;
		}
	}
	if ($pPktInfo!=0) {
		$accepted=1;
		$$pTcpInfo{seq}=$$pPktInfo{tcpseq}+$$pPktInfo{msg_len};
		if ($g_debug) {
			$$pPktInfo{displayinfo}.="Extra Information: TCP ACCEPTED $$pPktInfo{msg_len} Bytes.\n";
			my $aa=$$pPktInfo{tcpseq}+$$pPktInfo{msg_len};
			## print "$g_phy_frame TCP ACCEPTED $pTcpInfo $$pPktInfo{frame} $$pPktInfo{tcpseq} $aa $$pPktInfo{msg_len} Bytes.\n";
		}
	}
	if ( ($accepted!=0) && ($pNextPktInfo==0) ) {
		$pNextPktInfo=getNextTcpPacketFromQue($pTcpInfo,1);
		if ($pNextPktInfo!=0) {
			## print "$g_phy_frame DEQUEUED     $pTcpInfo $$pNextPktInfo{frame} $$pTcpInfo{seq} $$pNextPktInfo{tcpseq} $$pNextPktInfo{msg_len} Bytes.\n";
		}
		sub queTcpPacketOnQue {
			my($pTcpInfo,$pPktInfo)=@_;
			if ($pPktInfo==0) {
				return;
			};
			my $nextpktque=$$pTcpInfo{nextpktque};
			push @{$nextpktque},$pPktInfo;
			my $pktque=$$pTcpInfo{pktque};
			my $aa=1+$#{$nextpktque} + 1+$#{$pktque};
			displayTcpPacket($pPktInfo,"Enqued $aa",1);
		}
		sub getNextTcpPacketFromQue{
			my($pTcpInfo,$join_ques)=@_;
			my($pktque);
			my $quelen=0;
			my @yy;
			my $pNextPktInfo=0;
			if ($join_ques!=0) {
				my($nextpktque);
				$nextpktque=$$pTcpInfo{nextpktque};
				push @{$$pTcpInfo{pktque}},@{$$pTcpInfo{nextpktque}};
				my @xx=();
				$$pTcpInfo{nextpktque}=\@xx;
				$quelen =$#{$$pTcpInfo{pktque}};
				if ($quelen>=0) {
					sub xxsort {
						my($pPktInfo,$seq)=@_;
						my $bb=-$$pPktInfo{tcpseq};
						return $seq-$bb;
					}
					my $pTmpPktInfo;
					my $seq=$$pTcpInfo{seq};
					##foreach $pTmpPktInfo ( @{$$pTcpInfo{pktque}}) { print "$pTmpPktInfo "; } print "\n";
					##foreach $pTmpPktInfo ( @{$$pTcpInfo{pktque}}) { print "$$pTmpPktInfo{tcpseq} "; } print "\n";
					@yy = sort { &xxsort($a,$seq) <=> &xxsort($b,$seq) } @{$$pTcpInfo{pktque}};
					## foreach $pTmpPktInfo ( @yy) { print "$$pTmpPktInfo{tcpseq} "; } print "\n";
					$$pTcpInfo{pktque}=\@yy;
					## displayPacket($pNextPktInfo,"DQUE",0);
					$quelen =$#{$$pTcpInfo{pktque}};
				}
			} else {
				$quelen =$#{$$pTcpInfo{pktque}};
				@yy=@{$$pTcpInfo{pktque}};
			}
			$pNextPktInfo=shift @yy;
			$$pTcpInfo{pktque}=\@yy;
			if (!defined $pNextPktInfo) {
				$pNextPktInfo=0;
			};
			$pktque=$$pTcpInfo{pktque};
			my $nextpktque=$$pTcpInfo{nextpktque};
			my $aa=1+$#{$nextpktque} + 1+$#{$pktque};
			displayTcpPacket($pNextPktInfo,"Dequed $aa",1);
			return $pNextPktInfo;
		}
	} elsif ($pNextPktInfo==0) {
		$pNextPktInfo=getNextTcpPacketFromQue($pTcpInfo,0);
	}
	if (0) {
		 printf("TCPPKT:$$pPktInfo{frame} %-45s diff=%7d (%x %x %x,%4x)  len=%5d q=%2d \n",
			$conn,
			# don't tmpa,
			$$pPktInfo{tcpseq},
			$$pPktInfo{msg_len} + $$pPktInfo{tcpseq},
			$$pTcpInfo{nextseq},
			$$pPktInfo{iphdr_id},
			$$pPktInfo{msg_len},
			$#{$$pTcpInfo{nextpktque}}
			);
	}
	displayTcpPacket($pPktInfo,"TCP SENT",2);
	return ($pPktInfo,$pNextPktInfo);
}

sub handle_end_of_file_tcp_pktques{
	my($nextpktque,$pktque,$offset,$hdr_len,$pNextPktInfo)=(0,0,0,0,0);
	my($conn);
	my $qty=0;
	## if (defined %tcpInfo) 
	{
		foreach $conn (keys(%tcpInfo)) {
			my $pTcpInfo=\%{$tcpInfo{$conn}};
			releaseTcpPktQueues($pTcpInfo);
		}
	}
}

sub releaseTcpPktQueues {
	my ($pTcpInfo)=@_;
	my ($pNextPktInfo,$pPktInfo,$pPktInfo2);
	while (1) {
		$pNextPktInfo=getNextTcpPacketFromQue($pTcpInfo,1);
		if ($pNextPktInfo==0) { return;};
		$$pTcpInfo{seq}=$$pNextPktInfo{tcpseq};
		## queTcpPacketOnQue ($pTcpInfo,$pNextPktInfo);
		while ($pNextPktInfo!=0) {
			$pPktInfo=$pNextPktInfo;
			$pNextPktInfo=0;
			($pPktInfo2,$pNextPktInfo) = parse_tcp_header($pPktInfo,"term");
			$pPktInfo=$pPktInfo2;
			if ($pPktInfo) {
				process_tcp_udp_pkt($pPktInfo);
				$pPktInfo=0;
			}
		}
	}
}

## This subroutine check if the message starts with a SIP request or a SIP Response
# Returns ERROR if not a SIP message
# Returns OK if seems to be a SIP message
sub isSipMessage{
	my($pPktInfo)=@_;
	my($start,$index,$line);
	$start=$$pPktInfo{msg_offset};
	$index=index($$pPktInfo{pkt},"\r\n",$start);
	if ($index>=0) {
		$line=substr($$pPktInfo{pkt},$start,$index-$start);
		if ( ($line =~ /^\s*(sip\/\d+\.\d+)\s+(\d+)\s+(.*)$/i ) || ($line =~ /^\s*(\w+)\s*(.*)\s+(sip\/\d+\.\d+)\s*$/i ) ) {
			return "OK";	
		}
		## HTTP if ( ($line =~ /^\s*(http\/\d+\.\d+)\s+(\d+)\s+(.*)$/i ) || ($line =~ /^\s*(\w+)\s*(.*)\s+(http\/\d+\.\d+)\s*$/i ) ) { return "OK";	}
	}
	return "ERROR";
}

sub parseSipPacket {
	my($pPktInfo)=@_;
	my($pNextPktInfo,$pPktInfo2);
	$$pPktInfo{sipmsg}= substr($$pPktInfo{pkt}, $$pPktInfo{msg_offset}, $$pPktInfo{msg_len});
	## if ( ($$pPktInfo{frame}>=6) && ($$pPktInfo{frame}<=10) ) { displayPacket($pPktInfo,"",1); }
	## delete $$pPktInfo{pkt};
	while ($pPktInfo != 0) {
		($pPktInfo2,$pNextPktInfo) = parseSipMessage($pPktInfo,"");
		$pPktInfo=$pPktInfo2;
		if ($pPktInfo != 0 ) {
			execute_dynamic_call_filters ($pPktInfo);

			if ($g_pStartPktInfo==0) {
				$g_pStartPktInfo=$pPktInfo;
			}
			process_symmetric_upd_port_detection ($pPktInfo);
			
			addCallidIfNotPresent($pPktInfo);
			addFrameToList($pPktInfo);
			$pPktInfo=0;
		} else {
		}
		if ($pNextPktInfo !=0) {
			$pPktInfo=$pNextPktInfo;
		}
	}
}

sub handleSipPacketIncomplete {
	my($qty,$pPktInfo2,$pNextPktInfo,$retcode,$pPktInfo,$key,%sorted,$number);
	$qty=0;
	$g_phy_frame++;
	foreach $key (sort keys(%g_prevMsg)) {
		$pPktInfo=$g_prevMsg{$key};
		$sorted{frame}=$pPktInfo;
		$qty++;
	}
	if ($qty==0) {return;};
	$number=1;
	foreach $key (sort keys(%sorted)) {
		$pPktInfo=$sorted{$key};
		## displayPacket($pPktInfo,"hand",0);
		## $$pPktInfo{reorder_frame}=$g_phy_frame;
		## $$pPktInfo{reorder_subframe}=$number;
		## $$pPktInfo{reorder_unique}=++$g_unique_value;
		## &displayPacket($pPktInfo,"<Incomplete Packet at end of Scenario>");
		($pPktInfo2,$pNextPktInfo) = parseSipMessage($pPktInfo,"end");
		addCallidIfNotPresent($pPktInfo);
		addFrameToList($pPktInfo);
		$number++;
	}
	%g_prevMsg=();
	%sorted=();
}

# $$pPktInfo{sipmsg};				input and output
# $$pPrevPktInfo{event}="sip incomplete";   	output
sub parseSipMessage  {
	my($pPktInfo,$terminated)=@_;
	my($pNextPktInfo,$index2,$index,$phy_pkt,$key,$prevMsg,$port,$flag,$n,$line);
	my $conn=$$pPktInfo{connectid};
	## displayPacket($pPktInfo,"BEFORE",2);
	if (!defined $conn) {
		if ($g_debug!=0) {
			displayPacket($pPktInfo,"parseSipMessage:NotDefined",1);
		}
	}
	$$pPktInfo{msgtype}="SIP";
	$index=index($$pPktInfo{sipmsg},"\r\n",0);
	$line=substr($$pPktInfo{sipmsg},0,$index);
	my $pPrevPktInfo=0;
	while (exists($g_prevMsg{$conn}) && ($pPrevPktInfo==0) ) {
		## displayPacket($pPktInfo,"PREV PKT",2);
		$pPrevPktInfo=$g_prevMsg{$conn};
		delete $g_prevMsg{$conn};
		if ($pPktInfo == $pPrevPktInfo) {
			$pPrevPktInfo=0;
			next;
		}
		if (
			# New packet has valid Sip Header
			( ($line =~ /^\s*(sip\/\d+\.\d+)\s+(\d+)\s+(.*)$/i ) || ($line =~ /^\s*(\w+)\s*(\S+)\s+(sip\/\d+\.\d+)\s*$/i )) &&
			# and the old packet has a single line
			(index($$pPrevPktInfo{sipmsg},"\r\n",0)>=0)
		) {
			# the new message seems to be a complete message by itself
			# so the previous is an incomplete message
			#
			$$pPrevPktInfo{event}="sip incomplete";
			$$pPrevPktInfo{displayinfo}.="Extra Information: Packet is not a complete SIP message\n";
			return ($pPrevPktInfo,$pPktInfo);
		} else {
			## The new message is not the start of a sip message so
			# concatentate messages and ignore the previous message
			$$pPktInfo{displayinfo} = $$pPrevPktInfo{displayinfo}."$$pPktInfo{displayinfo}Extra Information: Packet was continued from "
				. "Frame=".&frameStr($pPrevPktInfo)."\n";
			$$pPktInfo{sipmsg}= $$pPrevPktInfo{sipmsg}.$$pPktInfo{sipmsg};
			## $g_packets_deleted++;
			delete $$pPrevPktInfo{sipmsg};
			delete $$pPktInfo{sippart};
			delete $$pPktInfo{content};
			$g_filtered_packets++;
			my $filter_str="Fragmented Sip Packets joined";
			$filter_str .= ". \@Frame=".&frameStr($pPktInfo) if ($g_debug!=0);
			$g_filter_cause{"$filter_str"}++;
		}
	}
	
	## print STDERR "$$pPktInfo{direct} $src_loc($srcip) $dst_loc($dstip)\n";

	# Parse msg into sip message and sipContent
	# Place msg into an array of lines stripping off crlf
	my $sipContentType="";

	## parse each line . splitting msg into sip / application parts. finding sipContenttype, sip request/result

	delete $$pPktInfo{nomedia};
	delete $$pPktInfo{hold};
	delete $$pPktInfo{sipresult};
	delete $$pPktInfo{sipresultdesc};
	delete $$pPktInfo{sipmethod};
	delete $$pPktInfo{sipmethodinfo};
	delete $$pPktInfo{event};
	## always keep commented out delete $$pPktInfo{sipcallid};
	## always keep commented out delete $$pPktInfo{sipcallnumber};
	delete $$pPktInfo{contentlength};
	delete $$pPktInfo{contenttype};
	delete $$pPktInfo{shortcontenttype};
	delete $$pPktInfo{sippart};
	delete $$pPktInfo{content};

	my $g_msg=$$pPktInfo{sipmsg};
	if (!defined $$pPktInfo{sipmsg}) {
		if ($g_debug!=0) {
			displayPacket($pPktInfo,"Undefined {sipmsg}",1);
		}
		$g_msg="";
	}
	my $g_sip_msg="";
	my $g_sip_terminator="\r\n\r\n";
	$index2=index($g_msg,$g_sip_terminator,0);
	if ($index2<0) {
		## print "DRE DEBUG INCOMPLETE SIP\n" ;
		## have an incomplete sip message part.
		if ($index>=0) {
			## have a line
		} else {
			## this is probable garbage.
		}
		if ($terminated eq "") {
			$g_prevMsg{$$pPktInfo{connectid}}=$pPktInfo;
			return (0,0);
		}
		$$pPktInfo{displayinfo}.="Extra Information: Packet is not a complete SIP message\n";
		parseSipPart($pPktInfo,\$g_msg,\"");
		return ($pPktInfo,0);
	} else {
		## have a complete SIP part
		# now get content.
		my $msg_len=length($g_msg);
		my $extracted=$index2+length($g_sip_terminator) ;
		my $sippart= substr($g_msg,0,$extracted);
		my $content_len=0;
		if ($sippart =~/\n((Content-Length)|(l))\s*:\s*(\d+)\s*\r/i) {
			$content_len=$4;
		}
		if ($msg_len<$content_len+$extracted) {
			## have incomplete message
			## print "DRE DEBUG INCOMPLETE CONTENT\n" ;
			if ($terminated eq "") {
				$g_prevMsg{$$pPktInfo{connectid}}=$pPktInfo;
				return (0,0);
			}
			$$pPktInfo{displayinfo}.="Extra Information: Packet is not a complete SIP message\n";
			parseSipPart($pPktInfo,\$sippart,\"");
			return ($pPktInfo,0);
		}
		$pNextPktInfo=0;
		if ($msg_len>$content_len+$extracted) {
			## print "DRE DEBUG EXTRA\n" ;
			## must create a new message
			my(%a,$a,$rem);
			$rem=$content_len+$extracted;
			%a=%{$pPktInfo};
			$a{subframe}=1+$$pPktInfo{subframe};
			$a{sipmsg}=substr($g_msg,$rem,$msg_len-$rem);
			$pNextPktInfo=\%a;
			## displayPacket($pNextPktInfo,"parseSipMessage:NextMsg",2);
			$msg_len=$rem;
			$g_packets_added++;
			$$pNextPktInfo{displayinfo} .="Extra Information: Packet was started from "
				. "Frame=".&frameStr($pPktInfo)."\n";
			$$pPktInfo{displayinfo} .="Extra Information: Frame:contained more than one Sip Message\n"
		}
		if ($msg_len>=$content_len+$extracted) {
			## $$pPktInfo{displayinfo}.="Extra Information: Packet is a complete SIP message\n";
			## have complete msg
			my $conpart= substr($g_msg,$extracted,$content_len);
			## print "$sippart";
			parseSipPart($pPktInfo,\$sippart,\$conpart);
			return ($pPktInfo,$pNextPktInfo);
		} else {
		}
	}
	## $$pPktInfo{displayinfo}.="Extra Information: Packet is an incomplete SIP message\n";
	return (0,0);
}

sub parseSipPart {
	my($pPktInfo,$sippart,$conpart)=@_;
	$$pPktInfo{sippart}=$$sippart;
	$$pPktInfo{contentlength}=length $$conpart;
	$$pPktInfo{content}=$$conpart;
	$$pPktInfo{sipmsg}=$$sippart.$$conpart;
	if ($$sippart =~ /^\s*(\w+)\s*(.*)\s+(sip\/\d+\.\d+)\s*\r\n/i ) {
		## print "DRE DEBUG FOUND REQUEST\n" ;
		## INVITE sip:2112@10.70.200.211:5060;line=1 SIP/2.0
		$$pPktInfo{sipmethod}=$1;
		$$pPktInfo{sipmethodinfo}=$2;
		$$pPktInfo{event}="sip request";
		$g_confirmed_sip_connections{$$pPktInfo{connectid}}=$pPktInfo;
	} elsif ($$sippart =~ /^\s*(sip\/\d+\.\d+)\s+(\d+)\s+(.*)\r\n/i ) {
		## SIP/2.0 180 Ringing
		## print "DRE DEBUG FOUND RESPONSE\n" ;
		$$pPktInfo{sipresult}=$2;
		$$pPktInfo{sipresultdesc}=$3;
		$$pPktInfo{event}="sip response";
		$g_confirmed_sip_connections{$$pPktInfo{connectid}}=$pPktInfo;
	} elsif (exists($g_confirmed_sip_connections{$$pPktInfo{connectid}}) ) {
		## print "DRE DEBUG SIP FRAGMENT\n" ;
		$$pPktInfo{event}="sip fragment";
		my $pPkt=$g_confirmed_sip_connections{$$pPktInfo{connectid}};
		$$pPktInfo{displayinfo}.="Extra Information: Packet does NOT contain a SIP Header but was in the same connection as "
				. "Frame=".&frameStr($pPkt)."\n";
		return;
	} else {
		print "DRE DEBUG SIP INVALID HDR\n" ;
		$$pPktInfo{displayinfo}.="Extra Information: Packet does NOT contain a SIP Header\n";
		$$pPktInfo{event}="invalid sip hdr";
		return;
	}
	$$pPktInfo{shortcontenttype}="";
	if ($$sippart =~/\n((Content-Type)|(c))\s*:\s*([^\r]+)\s*\r/i) {
		my $sipContentType=$4;
		if ($sipContentType =~ /^([^;]*);/) {
			$sipContentType=$1;
		}
		my $shortSipContentType=$sipContentType;
		if ($shortSipContentType =~ /(\w+)$/i) {
			$shortSipContentType="($1)";
		}
		$shortSipContentType=~tr/A-Z/a-z/;
		## print "DRE DEBUG CONTYPE $sipContentType\n" ;
		if ($sipContentType =~ /^\s*application\/sdp\s*$/i) {
			## print "DRE DEBUG CONTYPE $sipContentType\n$$conpart\n" ;
			## have SDP
			if ($$conpart =~ /\n\s*o\s*=\w+\s+\w+\s+\w+\s+\w+\s+\w+\s+0.0.0.0\s*/) {
				$shortSipContentType="(hold)";
				## print "DRE DEBUG HOLD\n" ;
			}
			## m=audio 8768 RTP/AVP 96 97 0 8 18 98
			my $xx=$$conpart;
			my $mediaFound=0;
			my $mediaPortFound=0;
			while ($xx =~ /\n\s*m\s*=\S+\s+(\d+)\s+[^\r]*\r/) {
				$xx=$';
				$port=$1;
				$mediaFound=1;
				if ($port!=0) {
					$mediaPortFound=1;
					$xx="";
					## print "DRE DEBUG MEDIA PORT\n" ;
				} else {
					## print "DRE DEBUG MEDIA NO PORT\n" ;
				}
			}
			if ( ($mediaFound!=0) && ($mediaPortFound==0)) {
				$shortSipContentType="(noMedia)";
			}
		}
		$$pPktInfo{shortcontenttype}=$shortSipContentType;
	}
	if ($$sippart =~ /\n((Call-ID)|(i))\s*:\s*(\S+)\s*\r/i) {
		##Call-ID: call-1044889121-25@10.70.200.218
		my $sipCallId=$4;
		## print "DRE DEBUG $sipCallId\n" ;
		$$pPktInfo{sipcallid}=$sipCallId;
		addCallidIfNotPresent($pPktInfo);
		if (0) {
		my $sipCallIdShort=$g_callId{$sipCallId};
		if (!defined($sipCallIdShort)) {
			$sipCallIdShort=++$g_nextCallId;
			$g_callId{$sipCallId}= $sipCallIdShort;
			$g_callId[$sipCallIdShort]=$sipCallId;
		}
		$$pPktInfo{sipcallnumber}=$sipCallIdShort;
		$g_connectCallidShort{$$pPktInfo{connectid}}=$sipCallIdShort;
		}
	} else {
		$$pPktInfo{event}="No Call Id";
	}
}

sub addCallidIfNotPresent {
	my($pPktInfo)=@_;
	my $sipCallIdShort = $$pPktInfo{sipcallnumber};
	my $sipCallId = $$pPktInfo{sipcallid};
	if (!defined($sipCallIdShort)) {
		## All SIP CALLS must have callid
		if (!defined($sipCallId)) {
			## have a SIP message without callid.
			#so assign one.
			$sipCallIdShort = $g_connectCallidShort{$$pPktInfo{connectid}};
			if (!defined($sipCallIdShort)) {
				$sipCallIdShort=++$g_nextCallId;
				$sipCallId="AssignedCallId:$sipCallIdShort";
				$g_callId{$sipCallId}= $sipCallIdShort;
				$g_callId[$sipCallIdShort]=$sipCallId;
				$g_connectCallidShort{$$pPktInfo{connectid}}=$sipCallIdShort;
			} else {
				$sipCallId=$g_callId[$sipCallIdShort];
			}
		} else {
			## have A SIP message with callid
			# get call Number (callidshort)
			$sipCallIdShort= $g_callId{$sipCallId};
			if (!defined($sipCallIdShort)) {
				# NO call Number (callidshort)
				# Ist occurance of callid
				# Get next call number.
				$sipCallIdShort=++$g_nextCallId;
				$g_callId{$sipCallId}= $sipCallIdShort;
				$g_callId[$sipCallIdShort]=$sipCallId;
				$g_connectCallidShort{$$pPktInfo{connectid}}=$sipCallIdShort;
			}

		}
		$$pPktInfo{sipcallnumber}= $sipCallIdShort;
		$$pPktInfo{sipcallid}=$g_callId[$sipCallIdShort];
	} else {
		## All SIP CALLS must have callid
		if (!defined($sipCallId)) {
			## have a SIP message without callid.
			#so assign one.
			$sipCallId=$g_callId[$sipCallIdShort];
			if (!defined($sipCallId)) {
				$sipCallId="AssignedCallId:$sipCallIdShort";
			}
			$$pPktInfo{sipcallid}=$sipCallId;
		}
	}
	## displayPacket($pPktInfo,"",1);
}

sub displayTcpPacket {
	return;
	my($pPktInfo,$place,$long)=@_;
	if (!defined $pPktInfo) {return;};
	if ($pPktInfo==0) {return;};

	my($conn,$conns,$pStartPktInfo);
	$pStartPktInfo=$$pPktInfo{tcpstartpkt};
	my $seqdiff=$$pPktInfo{tcpseq}-$$pStartPktInfo{tcpseq};

	my($package, $filename, $line) = caller;
	my$data="";
	if ( (defined $long) && ($long>=2)) { 
		if (defined $$pPktInfo{pkt}) {
			$data=substr($$pPktInfo{pkt}, $$pPktInfo{msg_offset}, $$pPktInfo{msg_len});
		}
	};
	printf("%5d,$long) TCP[%5d] %10d %5d %2s %10s:%s\n",
		$line,
		$$pPktInfo{frame},
		## "<$$pPktInfo{connectid}> $$pPktInfo{tcpconnectidshort}",
		$seqdiff,
		$$pPktInfo{msg_len},
		$$pPktInfo{tcpconnectidshort},
		$place,
		$data
		);
}

sub displayPacket {
	my($pPktInfo,$place,$long)=@_;
	if ($pPktInfo==0) {return;};
	my $crlf="";
	if ($long !=0) {$crlf="\n";};
	my($b,$c);
	my($package, $filename, $line) = caller;
	my($ra,$rb,$rc,$rd,$re)=("","","","",0);
	if (defined $$pPktInfo{reorder_frame}) { $ra=$$pPktInfo{reorder_frame};};
	if (defined $$pPktInfo{reorder_subframe}) { $rb=$$pPktInfo{reorder_subframe};};
	if (defined $$pPktInfo{reorder_unique}) { $rc=$$pPktInfo{reorder_unique};};
	if (defined $$pPktInfo{unique}) { $rd=$$pPktInfo{unique};};
	if (defined $$pPktInfo{contentlength}) { $re=$$pPktInfo{contentlength};};
	$ra="$ra.$rb.$rc";
	$rb="$$pPktInfo{frame}.$$pPktInfo{subframe}.$rd";
	## return;
	## return if ($$pPktInfo{frame}<15);
	## return if ($$pPktInfo{frame}>15);
	$rc=$$pPktInfo{sipcallnumber};
	if (!defined $rc) {$rc=0;};
	if (!defined $long) {$long=0;};
	my $seq="";
	if (defined $$pPktInfo{tcpseq}) {
		## $seq=sprintf "seq %8x %4d ",$$pPktInfo{tcpseq},$$pPktInfo{msg_len};
	};
	my $event= $$pPktInfo{event};
	if (!defined $event) {$event="";};
	printf("${crlf}Packet line=%5d callid=%-2d cl=%-3d frame=%6s %6s %-20s $seq %6s mode=$long $$pPktInfo{len}\n",
		$line,
		$rc,
		$re,
		$rb,
		$ra,
		$event,
		$place );
	return if $long <=0;
	foreach  $b (sort keys (%{$pPktInfo} )) {
		$c= ${$pPktInfo}{$b};
		if (!defined $c) {$c="..<undefined>..";};
		if ($long<=1) {
			next if ($b eq "sipmsg") ;
		}
		if ($long<=2) {
			next if ($b eq "sippart");
			next if ($b eq "content");
		}
		next if ($b eq "pkt");
		printf("\t%20s $c \n",$b);
	}
}

sub addFakeMessage {
	my($arg)=@_;
	## print __LINE__."  arg=$arg\n";
	my($calln,$srcip,$loc,$dstip,$dest,$xmsg,$pPktInfo,%pktInfo);
	my($dest1,$dest2,$flag,$sipPart,$contentPart,$callid,$a,$b,@xmsg);
	$pPktInfo=\%pktInfo;
	$sipPart="";
	$contentPart="";
	if ($arg =~ /^-fake:(\d+[.]\d+[.]\d+[.]\d+):(\d+[.]\d+[.]\d+[.]\d+):([^:]+):(\d+)[.]{0,1}(\d+){0,1}:(.*)$/ ) { 
		$$pPktInfo{event}="fake";
		$srcip=$1;
		$dstip=$2;
		$calln=$3;
		$dest=$4;
		$dest1=$4;
		if (defined($5)) { $dest2=$5; } else { $dest2=0; }
		$xmsg=$6;
	} elsif ($arg =~ /^-fake:([^:]+):([^:]+):([^:]+):(\d+)[.]{0,1}(\d+){0,1}:(.*)$/ ) { 
		$srcip=$1;
		$dstip=$2;
		$srcip=$g_ip_addr_by_alias{$1};
		$dstip=$g_ip_addr_by_alias{$2};
		$$pPktInfo{event}="fake noip";
		$calln=$3;
		$dest1=$4;
		if (defined($5)) { $dest2=$5; } else { $dest2=0; }
		$xmsg=$6;
	} else {
		print STDERR "ERROR:Undefined arg:$arg\n";
		exit_rtn(-1);
	}
	## displayPacket($pPktInfo,"",2);

	@xmsg=split_on_newline($xmsg);
	$flag=0;
	foreach $b (@xmsg) {
		if ($b =~ /^\s*$/ ) {
			$flag=1;
		} elsif ($flag==0) {
			$sipPart.=$b."\r\n";
		} else {
			$contentPart.=$b."\r\n";
		}
	}

	if ($contentPart ne "") {
		$contentPart.="\r\n";
	}

	## print __LINE__."$calln\n";
	if ($calln =~ /^\d+$/) {
		##Call-ID: call-1044889121-25@10.70.200.218
		$$pPktInfo{sipcallnumber}=$calln;
		addCallidIfNotPresent($pPktInfo);
		$calln=$$pPktInfo{sipcallid};
	} else {
	}
	$sipPart.=sprintf("Call-ID:FakeCallid:$calln\r\n");

	$sipPart.=sprintf("Content-Length : %d\r\n\r\n",length($contentPart));
	## print __LINE__." $sipPart\n";

	$g_unique_value++;
	$$pPktInfo{time}="0.0";    ## should be first real packet if any.
	$$pPktInfo{srcip}=$srcip;
	$$pPktInfo{dstip}=$dstip;
	$$pPktInfo{sippart}=$sipPart;
	$$pPktInfo{content}=$contentPart;
	$$pPktInfo{reorder_frame}=$dest1;
	$$pPktInfo{reorder_subframe}=$dest2;
	$$pPktInfo{reorder_unique}=$g_unique_value;
	$$pPktInfo{frame}=$dest1;
	$$pPktInfo{subframe}=$dest2;
	$$pPktInfo{unique}=$g_unique_value;
	$$pPktInfo{transport}="Inserted";
	$$pPktInfo{connectid}="inserted:$srcip:$dstip";
	$$pPktInfo{arg}=$arg;
	$$pPktInfo{srcport}=5060;
	$$pPktInfo{dstport}=5060;
	$$pPktInfo{displayinfo}="Extra Information: This is a Fake Message\n";
	$$pPktInfo{sipmsg}=$sipPart.$contentPart;
	parseIpAddr($srcip);
	parseIpAddr($dstip);
	$g_ip_addr__used{$$pPktInfo{srcip}}=1;;
	$g_ip_addr__used{$$pPktInfo{dstip}}=1;;

	## $$pPktInfo{sipcallnumber}=$sipCallIdShort;
	## $g_connectCallidShort{$$pPktInfo{connectid}}=$sipCallIdShort;
	parseSipMessage($pPktInfo,"end");
	## displayPacket($pPktInfo,"",2);

	addCallidIfNotPresent($pPktInfo);
	addFrameToList($pPktInfo);
}

my ($g_specFileFormat);
sub writePacketHdr{
	my ($magic,$major,$minor,$timezone,$filelen,$future,$linktype) = (0xA1B2C3D4,2,4,0,0,65535,1);
	my $filehdr;
	if ($g_specFileFormat eq "little") {
		$filehdr = pack("VvvVVVV",$magic,$major,$minor,$timezone,$filelen,$future,$linktype);
	} else {
		$filehdr = pack("NnnNNNN",$magic,$major,$minor,$timezone,$filelen,$future,$linktype);
	}
	print OUTFILE $filehdr;  
}


sub writePacket{
	my($sec,$usec,$pkt)=@_;
	my($pkthdr);
	my($len)=length($pkt);
	if ($g_specFileFormat eq "little") {
		$pkthdr =pack("VVVV",$sec,$usec,$len,$len);
	} else {
		$pkthdr =pack("NNNN",$sec,$usec,$len,$len);
	}
	print OUTFILE $pkthdr;  
	print OUTFILE $pkt;  
	## print STDERR __LINE__." $sec $usec \n";1.1.	Network Connections
}


my ($g_split_tcp,%g_translateip,$g_translateip,$g_special_ops_2nd_filename);

sub specialOpsArg {
	my($arg)=@_;
	if ($arg =~ /^-splittcp:(\d+)$/ ) {
		$g_split_tcp=$1;
		$g_special_operations=2;
	} elsif ($arg =~ /^-merge:(\S+)$/ ) {
		$g_special_operations=3;
		$g_special_ops_2nd_filename=$1;
		## print STDERR " SPEC :file:$g_special_ops_2nd_filename\n";
	} elsif ($arg =~ /^-copy$/ ) {
		$g_special_operations=2;
	} elsif ($arg =~ /^-stat$/ ) {  
		$g_special_operations=1;
	} elsif ($arg =~ /^-x:(\d+[.]\d+[.]\d+[.]\d+)\s*=\s*(\d+[.]\d+[.]\d+[.]\d+)\s*$/ ) {
		$g_translateip{$1}=$2;
		$g_translateip=1;
		$g_special_operations=2;
	} else {
		print STDERR "ERROR:Undefined arg:$arg\n";
		exit_rtn(-1);
	};
}


sub initSpecialOperations {
	if ($g_special_operations==0) { return; };
	$g_specFileFormat="big";
	$g_specFileFormat="little";
	if ( ($g_special_operations==2) || ($g_special_operations==3)){
		getBaseFileName();
		$g_outputTextFileName="${g_outputBaseDirName}${g_outputBaseName}.new.dump";
		if ( (0) && (-e $g_outputTextFileName) ) {
			print "$g_outputTextFileName file already exists.\n";
			exit_rtn(1);
		}
		unless (open(OUTFILE, ">$g_outputTextFileName") ) {
			print "*** ERROR:can't open for write $g_outputTextFileName$!\n";
			exit_rtn(-1);
		};  
		$g_outputTextFile=*OUTFILE;
		binmode OUTFILE;
		writePacketHdr();
	}
	if ($g_special_operations==3 ) {
		## have merge option.
		createPacketCache($g_infile,\&handleSpecialMergeOperations1stFile);
		$g_phy_frame-=1;
		print "File $g_infile contains $g_phy_frame Ethernet Frames\n";
		$g_phy_frame=-1;
		createPacketCache($g_special_ops_2nd_filename,\&handleSpecialMergeOperations2ndFile);
		$g_phy_frame-=1;
		print "File $g_special_ops_2nd_filename contains $g_phy_frame Ethernet Frames\n";
		handleSpecialMergeOperationsDoMerge();
		print "Output file File is $g_outputTextFileName\n";
	} else {
		createPacketCache($g_infile,\&handleSpecialOperations);
		print "File $g_infile contains $g_phy_frame Ethernet Frames\n";
	}
	exit_rtn(1);
}

my %g_merge_udp_pkts;
my $g_1st_timediff;
my $g_1st_pPktInfo_1st_file;
my $g_last_pPktInfo_1st_file;
my $g_1st_pPktInfo_2nd_file;
my $g_last_pPktInfo_2nd_file;

sub getIdenticalPacketId {
	my($pPktInfo)=@_;
	my $id =$$pPktInfo{srcip}.":".$$pPktInfo{dstip}.sprintf(":%d\n",$$pPktInfo{ipprotocol}).substr($$pPktInfo{pkt},$$pPktInfo{ipdata_offset});
	return $id;
}

sub getIdenticalPacket {
	my($pPktInfo)=@_;
	return $g_merge_udp_pkts{getIdenticalPacketId($pPktInfo)};
}
sub setIdenticalPacket {
	my($pPktInfo)=@_;
	$g_merge_udp_pkts{getIdenticalPacketId($pPktInfo)}=$pPktInfo;
}

sub handleSpecialMergeOperations1stFile {
	my($pPktInfo)=@_;
	if (!(&parse_frame($pPktInfo) =~ /ERROR/)) {
		if ($$pPktInfo{ipprotocol}== 6) {
			## tcp
		} elsif ($$pPktInfo{ipprotocol}== 17) {
			my $udphdr_len= unpack_udp_header($pPktInfo);
			if ( ($udphdr_len != ($$pPktInfo{ipdata_len})) || ( isSipMessage($pPktInfo) =~ /ERROR/) ) {
				return;
			}
			my $pPkt=getIdenticalPacket($pPktInfo);
			if (defined $pPkt ) {
				$$pPkt{qty}++;
				## Add to linked list of same packets.
				my $pPkt_next=$$pPkt{pDuplicateLastPktInfo};
				if (defined $pPkt_next) {
					$$pPkt_next{pDuplicatePktInfo}=$pPktInfo;
				} else {
					$$pPkt{pDuplicatePktInfo}=$pPktInfo;
				}
				$$pPkt{pDuplicateLastPktInfo}=$pPktInfo;
				## end of linked list
				# print STDERR "duplicate pkt for same file $$pPkt{qty} $$pPkt{frame} $$pPktInfo{frame} \n";
			} else {
				$$pPktInfo{qty}=1;
				setIdenticalPacket($pPktInfo);
			}
		} else {
			return;
		}
		## place on sinlge linked list
		if (defined $g_last_pPktInfo_1st_file ) {
			$$g_last_pPktInfo_1st_file{pNextPktInfo}=$pPktInfo;
		} else {
			$g_1st_pPktInfo_1st_file=$pPktInfo;
		}
		$g_last_pPktInfo_1st_file=$pPktInfo;
		## End of linked list
	} else {
		return;
	}
	return;
};

sub handleSpecialMergeOperations2ndFile {
	my($pPktInfo)=@_;
	if (!(&parse_frame($pPktInfo) =~ /ERROR/)) {
		if ($$pPktInfo{ipprotocol}== 6) {
			## tcp
		} elsif ($$pPktInfo{ipprotocol}== 17) {
			## udp
			my $pPkt=getIdenticalPacket($pPktInfo);
			## check if same packet in both files.
			if (defined $pPkt)  {
				my $pPkt_next=$$pPkt{pDuplicatePktInfo};
				## Check for duplicate packets in first file.
				## 1st file can not already to be syncn'ed with 2nd file.
				#if duplicates can not sync time.
				if ( (!defined $pPkt_next) && (!defined $$pPkt{timediffpkt} ) ) {
					my $timediff=$$pPktInfo{time} - $$pPkt{time};
					## Set time difference into 2nd file
					$$pPktInfo{timediff}=$timediff;
					$$pPktInfo{timediffpkt}=$pPkt;
					## Set time difference into 1st file
					$$pPkt{timediff}=$timediff;
					$$pPkt{timediffpkt}=$pPktInfo;
					if (! defined $g_1st_timediff) {
						$g_1st_timediff=$timediff;
					}
					# print STDERR "duplicate pkt for different file $$pPkt{qty} $$pPkt{frame} $$pPktInfo{frame} $timediff $$pPktInfo{time} $$pPkt{time} $$pPktInfo{len} \n";
				}
				#displayPacket($pPkt,"",1);
			} else {
			}
		} else {
			return;
		}

		## place on single linked list
		if (defined $g_last_pPktInfo_2nd_file ) {
			$$g_last_pPktInfo_2nd_file{pNextPktInfo}=$pPktInfo;
		} else {
			$g_1st_pPktInfo_2nd_file=$pPktInfo;
		}
		$g_last_pPktInfo_2nd_file=$pPktInfo;
		## End of linked list
	} else {
		return;
	}
	return;
};

sub handleSpecialMergeOperationsDoMerge {
	my $p1stFilePktInfo=$g_1st_pPktInfo_1st_file;
	my $p2ndFilePktInfo=$g_1st_pPktInfo_2nd_file;
	my $timediff=$g_1st_timediff;
	my $p1st_pPktInfo_merge_file=undef;
	my $time;
	my $pPkt;

	if (! defined $p1stFilePktInfo) {
		## can not do merge. no packets to merge.
		print STDERR "ERROR: NO TCP or SIP UDP PACKETS in file $g_infile Available for merging\n";
		exit_rtn(-1);
	}
	if ( ! defined $p2ndFilePktInfo ) {
		## can not do merge. no packets to merge.
		print STDERR "ERROR: NO TCP or SIP UDP PACKETS in file $g_special_ops_2nd_filename Available for merging\n";
		exit_rtn(-1);
	}
	if (! defined $timediff ) {
		## can not do merge. no packets to merge.
print STDERR "ERROR: NO identical SIP UDP Packets between files $g_infile and $g_special_ops_2nd_filename
use the mergecap program to combined two capture files together.
The mergecap program can be downloaded from the www.ethereal.com web site.
";
		exit_rtn(-1);
	}
	my $end=0;
	## make a pass throught the second file to find all packets that need to be removed based on time differences.
	while ( $end!=2) {
		if ($$p2ndFilePktInfo{ipprotocol}== 17) {
			## udp
			if (defined $$p2ndFilePktInfo{timediff} ) {
				$timediff=$$p2ndFilePktInfo{timediff};
				## print STDERR __LINE__." TimeSync 2nd File Frame      $$p2ndFilePktInfo{frame} Duplicate in 1st File Frame $$pPkt{frame} timediff=$timediff\n";
			} 
			my $pPkt=getIdenticalPacket($p2ndFilePktInfo);
			## check if same packet in both files.
			while (defined $pPkt) {
				## print __LINE__." $$p2ndFilePktInfo{frame} $$pPkt{frame}\n";
				## found an identical packet
				## Check for duplicate packets in first file.
				#Validate time. must be +- value.
				#
				my $diff=($$p2ndFilePktInfo{time}-$timediff) - $$pPkt{time};
				my $limit=0.151;
				if ( ($diff>= -$limit) && ($diff<= $limit) ) {
					## print __LINE__." Found Identical pkt 1st Frame=$$pPkt{frame} $$pPkt{time}   2nd $$p2ndFilePktInfo{frame} $$p2ndFilePktInfo{time} $diff \n";
					## ok to delete this sip message
					$$p2ndFilePktInfo{deletePacket}=$pPkt;
					$pPkt=undef;
				} else {
					## try next
					## print __LINE__." $$p2ndFilePktInfo{frame} $$pPkt{frame}   $$pPkt{pDuplicatePktInfo}{frame}\n";
					$pPkt=$$pPkt{pDuplicatePktInfo};
				}
			}
		}
		
		## Advanced 2nd file
		$p2ndFilePktInfo=$$p2ndFilePktInfo{pNextPktInfo};
		if (!defined $p2ndFilePktInfo) {
			$end|=2;
		}
	}


	$timediff=$g_1st_timediff;
	$p2ndFilePktInfo=$g_1st_pPktInfo_2nd_file;
	$end=0;
	my $qty_merged=0;
	my $time_1st;
	my $time_2nd;
	## bit 0 (value 1) represent end of packets in 1st file
	## bit 1 (value 2) represent end of packets in 2nd file
	
	%g_merge_udp_pkts=(); ## delete all elements
	$g_1st_pPktInfo_2nd_file=0;
	$g_1st_pPktInfo_1st_file=0;
	
	while ( $end!=3) {
		if (($end&1)==0) {
			$time_1st=$$p1stFilePktInfo{time};
		} else {
			$time_1st="??";
		}
		if (($end&2)==0) {
			$time_2nd=$$p2ndFilePktInfo{time}-$timediff;
		} else {
			$time_2nd="??";
		}
		## print __LINE__." $time_1st $time_2nd\n";
		# end for 2nd file or not end for 1st file and 1st file time <= 2nd file time.
		if  ( (($end&2)!=0) || ( (($end&1)==0) &&  ( ($time_1st <= $time_2nd ) )  ) ) {
			# Use packet from 1st file
			if (defined $$p1stFilePktInfo{timediff} ) {
				$timediff=$$p1stFilePktInfo{timediff};
				$pPkt=$$p1stFilePktInfo{timediffpkt};
				delete $$p1stFilePktInfo{timediff};
				## print STDERR __LINE__." TimeSync 1st File Frame      $$p1stFilePktInfo{frame} Duplicate in 2nd File frame= $$pPkt{frame} diff=$timediff\n";
				if (defined $pPkt) {
					delete $$pPkt{timediff};
				}
				next;
			} 
			$qty_merged++;
			# printf STDERR __LINE__." Merging  1st File Frame %-3d %5s to Frame %-3d  $$p1stFilePktInfo{time}\n",$$p1stFilePktInfo{frame},"          ",$qty_merged;
			writePacket($$p1stFilePktInfo{seconds},$$p1stFilePktInfo{usec},$$p1stFilePktInfo{pkt});

			## Advanced 1st file
			$p1stFilePktInfo=$$p1stFilePktInfo{pNextPktInfo};
			if (!defined $p1stFilePktInfo) {
				$end|=1;
			}
		# end for 1st file or not end for 2nd file and 1st file time > 2nd file time.
		} elsif ( (($end&1)!=0) || ( (($end&2)==0) &&  ( ($time_1st >= $time_2nd ) ) )) {
			# Use packet from 2nd file
			if (defined $$p2ndFilePktInfo{timediff} ) {
				$timediff=$$p2ndFilePktInfo{timediff};
				$pPkt=$$p2ndFilePktInfo{timediffpkt};
				## print STDERR __LINE__." TimeSync 2nd File Frame      $$p2ndFilePktInfo{frame} Duplicate in 1st File Frame $$pPkt{frame} timediff=$timediff\n";
				delete $$p2ndFilePktInfo{timediff};
				if (defined $pPkt) {
					delete $$pPkt{timediff};
				}
				next;
			} 
			## displayPacket($p2ndFilePktInfo,"",1);
			$pPkt=$$p2ndFilePktInfo{deletePacket};
			if (defined $pPkt ) {
				;  ## do nothing
				## print STDERR __LINE__." Deleting 2nd File Frame      $$p2ndFilePktInfo{frame} Duplicate in 1st File Frame $$pPkt{frame}\n";
			} else {
				## save message in 2nd file in correct place in first file.
				if ($time_2nd=~ /^(\d+)[.](\d+)$/) {
					## Correct time information in packet from 2nd file.
					# print STDERR __LINE__." $$p2ndFilePktInfo{seconds}.$$p2ndFilePktInfo{usec} $$p2ndFilePktInfo{time} ";
					$$p2ndFilePktInfo{seconds}=$1;
					$$p2ndFilePktInfo{usec}=substr($2."000000",0,6);
					$$p2ndFilePktInfo{time}=$time_2nd;
					# print STDERR __LINE__." $$p2ndFilePktInfo{seconds}.$$p2ndFilePktInfo{usec} $$p2ndFilePktInfo{time}\n";
				}
				$qty_merged++;
				# printf STDERR __LINE__." Merging  2nd File Frame %5s %3d to Frame %-3d  $$p2ndFilePktInfo{time}\n","          ",$$p2ndFilePktInfo{frame},$qty_merged;
				writePacket($$p2ndFilePktInfo{seconds},$$p2ndFilePktInfo{usec},$$p2ndFilePktInfo{pkt});
			}
			## Advanced 2nd file
			$p2ndFilePktInfo=$$p2ndFilePktInfo{pNextPktInfo};
			if (!defined $p2ndFilePktInfo) {
				$end|=2;
			}
		}
	}
}

sub handleSpecialOperations {
	my($pPktInfo)=@_;
	if ($g_special_operations==0) { return; };
	if ($g_special_operations==2) {
		if (!(&parse_frame($pPktInfo) =~ /ERROR/)) {
			## displayPacket($pPktInfo,"",1);
			if ($$pPktInfo{ipprotocol}== 6) {
				if (defined $g_translateip) {
					translateIpPkt($pPktInfo);
					translateTcpData($pPktInfo);
				}
				if (defined $g_split_tcp ) {
					unpack_tcp_header($pPktInfo);
					my $tcpl=$$pPktInfo{msg_len};
					my $endline=index($$pPktInfo{pkt},"\r\n",$$pPktInfo{msg_offset});
					my $minlen=$endline-$$pPktInfo{msg_offset};
					## displayPacket($pPktInfo,"",1);
					if ( ($tcpl >= 100 )  &&
						($endline>0) && ($minlen>0)
					) {
						my $quarter=$tcpl/4;
						my $third=$tcpl/3;
						my $n=0;
						insertFragment($pPktInfo,0,2+$minlen,++$n);
						insertFragment($pPktInfo,$third,$third,++$n)		if($g_split_tcp>1 );
						insertFragment($pPktInfo,$quarter,$quarter,++$n)	if($g_split_tcp>2 );
						insertFragment($pPktInfo,2*$quarter,$quarter,++$n)	if($g_split_tcp>3 );
						insertFragment($pPktInfo,0,$third,++$n)			if($g_split_tcp>4 );
						insertFragment($pPktInfo,2*$third,$third-10,++$n)	if($g_split_tcp>5 );
					}
				}
			} elsif ($$pPktInfo{ipprotocol}== 17) {
				if (defined $g_translateip) {
					translateIpPkt($pPktInfo);
					translateUdpData($pPktInfo);
				}
			} else {
				return;
			}
		} else {
			return;
		}
		writePacket($$pPktInfo{seconds},$$pPktInfo{usec},$$pPktInfo{pkt});
		return;
	}
	return;
};


sub translateTcpData {
	my ($pPktInfo)=@_;
	my ($key,$value,$msg,$changed);
	unpack_tcp_header ($pPktInfo);
	$msg = substr($$pPktInfo{pkt},$$pPktInfo{msg_offset},$$pPktInfo{msg_len});
	while (($key,$value) = each %g_translateip) {
		$msg =~ s/$key/$value/g;
	}
	substr($$pPktInfo{pkt},$$pPktInfo{msg_offset},$$pPktInfo{msg_len})=$msg;
	$$pPktInfo{msg_len}=length $msg;
};

sub translateUdpData {
	my ($pPktInfo)=@_;
	my ($key,$value,$msg,$changed);
	unpack_udp_header ($pPktInfo);
	$msg = substr($$pPktInfo{pkt},$$pPktInfo{msg_offset},$$pPktInfo{msg_len});
	while (($key,$value) = each %g_translateip) {
		$msg =~ s/$key/$value/g;
	}
	substr($$pPktInfo{pkt},$$pPktInfo{msg_offset},$$pPktInfo{msg_len})=$msg;
	$$pPktInfo{msg_len}=length $msg;
};


sub translateIpPkt {
	my ($pPktInfo)=@_;
	my($iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, $iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, @iphdr_srcaddr, @iphdr_destaddr,$srcip,$dstip,$filter_str);

	my ($ipx,$changed);
	$changed=0;
	## get basic part of IP header
	($iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, $iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, 
		$iphdr_srcaddr[0], $iphdr_srcaddr[1], $iphdr_srcaddr[2], $iphdr_srcaddr[3],
		$iphdr_destaddr[0], $iphdr_destaddr[1], $iphdr_destaddr[2], $iphdr_destaddr[3]
	)= unpack("CCnnnCCnCCCCCCCC",substr($$pPktInfo{pkt},14,20));

	if (exists($g_translateip{$$pPktInfo{srcip}}) ) {
		$ipx= $g_translateip{$$pPktInfo{srcip}};
		if ( $ipx =~ /(\d+).(\d+).(\d+).(\d+)/ ) {
			$iphdr_srcaddr[0]=$1;
			$iphdr_srcaddr[1]=$2;
			$iphdr_srcaddr[2]=$3;
			$iphdr_srcaddr[3]=$4;
			$changed=1;
		}
	}
	if (exists($g_translateip{$$pPktInfo{dstip}} )) {
		$ipx= $g_translateip{$$pPktInfo{dstip}};
		if ( $ipx =~ /(\d+).(\d+).(\d+).(\d+)/ ) {
			$iphdr_destaddr[0]=$1;
			$iphdr_destaddr[1]=$2;
			$iphdr_destaddr[2]=$3;
			$iphdr_destaddr[3]=$4;
			$changed=1;
		}
	}

	$iphdr_cksum=0;

	if ($changed!=0) {
		substr($$pPktInfo{pkt},14,20) = pack("CCnnnCCnCCCCCCCC",$iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, 
		$iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, 
		$iphdr_srcaddr[0], $iphdr_srcaddr[1], $iphdr_srcaddr[2], $iphdr_srcaddr[3],
		$iphdr_destaddr[0], $iphdr_destaddr[1], $iphdr_destaddr[2], $iphdr_destaddr[3]);
	};
}

sub insertFragment {
	my ($pPktInfo,$off,$len,$qty)=@_;
	my $pktdata=substr($$pPktInfo{pkt},$$pPktInfo{msg_offset},$$pPktInfo{msg_len});
	my $pkthdr=substr($$pPktInfo{pkt},0,$$pPktInfo{msg_offset});
	my($iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, $iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, @iphdr_srcaddr, @iphdr_destaddr,$srcip,$dstip,$filter_str);

	my (@tcphdr_flags,$tcphdr_srcport, $tcphdr_destport , $tcphdr_seq , $tcphdr_ack, $tcphdr_off, $tcphdr_flags, $tcphdr_winsize, $tcphdr_cksum, $tcphdr_urgentptr);


	## get basic part of IP header
	($iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, $iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, 
		$iphdr_srcaddr[0], $iphdr_srcaddr[1], $iphdr_srcaddr[2], $iphdr_srcaddr[3],
		$iphdr_destaddr[0], $iphdr_destaddr[1], $iphdr_destaddr[2], $iphdr_destaddr[3]
	)= unpack("CCnnnCCnCCCCCCCC",substr($$pPktInfo{pkt},14,20));

	$iphdr_cksum=0;
	$iphdr_pktlen -= ($$pPktInfo{msg_len}-$len); 

	substr($pkthdr,14,20) = pack("CCnnnCCnCCCCCCCC",$iphdr_len_ver, $iphdr_tos, $iphdr_pktlen, $iphdr_id, $iphdr_off, $iphdr_ttl, $iphdr_proto, $iphdr_cksum, 
		$iphdr_srcaddr[0], $iphdr_srcaddr[1], $iphdr_srcaddr[2], $iphdr_srcaddr[3],
		$iphdr_destaddr[0], $iphdr_destaddr[1], $iphdr_destaddr[2], $iphdr_destaddr[3]
	);



	($tcphdr_srcport, $tcphdr_destport , $tcphdr_seq , $tcphdr_ack, $tcphdr_off, $tcphdr_flags, $tcphdr_winsize, $tcphdr_cksum, $tcphdr_urgentptr)
		=unpack("nnNNCCnnn",substr($pkthdr,$$pPktInfo{ipdata_offset},20));
		$tcphdr_seq+=$off;
	substr($pkthdr,$$pPktInfo{ipdata_offset},20)=
		pack("nnNNCCnnn",$tcphdr_srcport, $tcphdr_destport , $tcphdr_seq , $tcphdr_ack, $tcphdr_off, $tcphdr_flags, $tcphdr_winsize, $tcphdr_cksum, $tcphdr_urgentptr);

	## if ($off+$len+$$pPktInfo{msg_offset}>$$pPktInfo{msg_len}) { return; };
	writePacket($$pPktInfo{seconds},(0*(($qty*100)+$$pPktInfo{usec})),$pkthdr.substr($pktdata,$off,$len));
}




## no strict;
## Copied perl module local.pm 
#
#
##changed croak to die
## package Time::Local;
## require 5.000;
## require Exporter;
##use Carp;

## @ISA		= qw( Exporter );
## @EXPORT		= qw( timegm timelocal );
## @EXPORT_OK	= qw( timegm_nocheck timelocal_nocheck );


sub timegm {
    my (@date) = @_;
    if ($date[5] > 999) {
        $date[5] -= 1900;
    }
    elsif ($date[5] >= 0 && $date[5] < 100) {
        $date[5] -= 100 if $date[5] > $breakpoint;
        $date[5] += $nextCentury;
    }
    $ym = pack("C2", @date[5,4]);
    my $cheat = $cheat{$ym} || &cheat(@date);
    $cheat
    + $date[0] * $SEC
    + $date[1] * $MIN
    + $date[2] * $HR
    + ($date[3]-1) * $DAY;
}

sub timegm_nocheck {
    local $options{no_range_check} = 1;
    &timegm;
}

sub timelocal {
    my $t = &timegm;
    my $tt = $t;

    my (@lt) = localtime($t);
    my (@gt) = gmtime($t);
    if ($t < $DAY and ($lt[5] >= 70 or $gt[5] >= 70 )) {
	# Wrap error, too early a date
	# Try a safer date
	$tt += $DAY;
	@lt = localtime($tt);
	@gt = gmtime($tt);
    }

    my $tzsec = ($gt[1] - $lt[1]) * $MIN + ($gt[2] - $lt[2]) * $HR;

    if($lt[5] > $gt[5]) {
	$tzsec -= $DAY;
    }
    elsif($gt[5] > $lt[5]) {
	$tzsec += $DAY;
    }
    else {
	$tzsec += ($gt[7] - $lt[7]) * $DAY;
    }

    $tzsec += $HR if($lt[8]);
    
    my $time = $t + $tzsec;
    my @test = localtime($time + ($tt - $t));
    $time -= $HR if $test[2] != $_[2];
    # print __LINE__ . " time=$time\n";
    $time;
}

sub timelocal_nocheck {
    local $options{no_range_check} = 1;
    &timelocal;
}
sub cheat {
    my $year = $_[5];
    my $month = $_[4];
    unless ($options{no_range_check}) {
	die "Month '$month' out of range 0..11" if $month > 11 || $month < 0;
	die "Day '$_[3]' out of range 1..31"	  if $_[3] > 31 || $_[3] < 1;
	die "Hour '$_[2]' out of range 0..23"	  if $_[2] > 23 || $_[2] < 0;
	die "Minute '$_[1]' out of range 0..59" if $_[1] > 59 || $_[1] < 0;
	die "Second '$_[0]' out of range 0..59" if $_[0] > 59 || $_[0] < 0;
    }
    my $guess = $^T;
    my @g = gmtime($guess);
    my $lastguess = "";
    my $counter = 0;
    my ($diff,$thisguess);
    while ($diff = $year - $g[5]) {
	## print __LINE__ . " year <$year> g <@g> g5<$g[5]> day<$DAY>\n";
	die "Can't handle date (".join(", ",@_).")" if ++$counter > 255;
	$guess += $diff * (363 * $DAY);
	@g = gmtime($guess);
	if (($thisguess = "@g") eq $lastguess){
	    die "Can't handle date (".join(", ",@_).")";
	    #date beyond this machine's integer limit
	}
	$lastguess = $thisguess;
    }
    while ($diff = $month - $g[4]) {
	die "Can't handle date (".join(", ",@_).")" if ++$counter > 255;
	$guess += $diff * (27 * $DAY);
	@g = gmtime($guess);
	if (($thisguess = "@g") eq $lastguess){
	    die "Can't handle date (".join(", ",@_).")";
	    #date beyond this machine's integer limit
	}
	$lastguess = $thisguess;
    }
    my @gfake = gmtime($guess-1); #still being sceptic
    if ("@gfake" eq $lastguess){
        die "Can't handle date (".join(", ",@_).")";
        #date beyond this machine's integer limit
    }
    $g[3]--;
    $guess -= $g[0] * $SEC + $g[1] * $MIN + $g[2] * $HR + $g[3] * $DAY;
    $cheat{$ym} = $guess;
}

sub kerberos_data_init {
	if ($g_kerberos<=0) {
		return;
	} else {
		my @errorCodes=();
			$errorCodes[0]="KDC_ERR_NONE";
			$errorCodes[1]="KDC_ERR_NAME_EXP";
			$errorCodes[2]="KDC_ERR_SERVICE_EXP";
			$errorCodes[3]="KDC_ERR_BAD_PVNO";
			$errorCodes[4]="KDC_ERR_C_OLD_MAST_KVNO";
			$errorCodes[5]="KDC_ERR_S_OLD_MAST_KVNO";
			$errorCodes[6]="KDC_ERR_C_PRINCIPAL_UNKNOWN";
			$errorCodes[7]="KDC_ERR_S_PRINCIPAL_UNKNOWN";
			$errorCodes[8]="KDC_ERR_PRINCIPAL_NOT_UNIQUE";
			$errorCodes[9]="KDC_ERR_NULL_KEY";
			$errorCodes[10]="KDC_ERR_CANNOT_POSTDATE";
			$errorCodes[11]="KDC_ERR_NEVER_VALID";
			$errorCodes[12]="KDC_ERR_POLICY";
			$errorCodes[13]="KDC_ERR_BADOPTION";
			$errorCodes[14]="KDC_ERR_ETYPE_NOSUPP";
			$errorCodes[15]="KDC_ERR_SUMTYPE_NOSUPP";
			$errorCodes[16]="KDC_ERR_PADATA_TYPE_NOSUPP";
			$errorCodes[17]="KDC_ERR_TRTYPE_NOSUPP";
			$errorCodes[18]="KDC_ERR_CLIENT_REVOKED";
			$errorCodes[19]="KDC_ERR_SERVICE_REVOKED";
			$errorCodes[20]="KDC_ERR_TGT_REVOKED";
			$errorCodes[21]="KDC_ERR_CLIENT_NOTYET";
			$errorCodes[22]="KDC_ERR_SERVICE_NOTYET";
			$errorCodes[23]="KDC_ERR_KEY_EXPIRED";
			$errorCodes[24]="KDC_ERR_PREAUTH_FAILED";
			$errorCodes[25]="KDC_ERR_PREAUTH_REQUIRED";
			$errorCodes[31]="KRB_AP_ERR_BAD_INTEGRITY";
			$errorCodes[32]="KRB_AP_ERR_TKT_EXPIRED";
			$errorCodes[33]="KRB_AP_ERR_TKT_NYV";
			$errorCodes[34]="KRB_AP_ERR_REPEAT";
			$errorCodes[35]="KRB_AP_ERR_NOT_US";
			$errorCodes[36]="KRB_AP_ERR_BADMATCH";
			$errorCodes[37]="KRB_AP_ERR_SKEW";
			$errorCodes[38]="KRB_AP_ERR_BADADDR";
			$errorCodes[39]="KRB_AP_ERR_BADVERSION";
			$errorCodes[40]="KRB_AP_ERR_MSG_TYPE";
			$errorCodes[41]="KRB_AP_ERR_MODIFIED";
			$errorCodes[42]="KRB_AP_ERR_BADORDER";
			$errorCodes[44]="KRB_AP_ERR_BADKEYVER";
			$errorCodes[45]="KRB_AP_ERR_NOKEY";
			$errorCodes[46]="KRB_AP_ERR_MUT_FAIL";
			$errorCodes[47]="KRB_AP_ERR_BADDIRECTION";
			$errorCodes[48]="KRB_AP_ERR_METHOD";
			$errorCodes[49]="KRB_AP_ERR_BADSEQ";
			$errorCodes[50]="KRB_AP_ERR_INAPP_CKSUM";
			$errorCodes[60]="KRB_ERR_GENERIC";
			$errorCodes[61]="KRB_ERR_FIELD_TOOLONG";
			$errorCodes[62]="KDC_ERROR_CLIENT_NOT_TRUSTED";
			$errorCodes[63]="KDC_ERROR_KDC_NOT_TRUSTED";
			$errorCodes[64]="KDC_ERROR_INVALID_SIG";
			$errorCodes[65]="KDC_ERR_KEY_TOO_WEAK";
			$errorCodes[66]="KDC_ERR_CERTIFICATE_MISMATCH";
			$errorCodes[67]="KRB_AP_ERR_NO_TGT";
			$errorCodes[68]="KDC_ERR_WRONG_REALM";
			$errorCodes[69]="KRB_AP_ERR_USER_TO_USER_REQUIRED";
			$errorCodes[70]="KDC_ERR_CANT_VERIFY_CERTIFICATE";
			$errorCodes[71]="KDC_ERR_INVALID_CERTIFICATE";
			$errorCodes[72]="KDC_ERR_REVOKED_CERTIFICATE";
			$errorCodes[73]="KDC_ERR_REVOCATION_STATUS_UNKNOWN";
			$errorCodes[74]="KDC_ERR_REVOCATION_STATUS_UNAVAILABLE";
			$errorCodes[75]="KDC_ERR_CLIENT_NAME_MISMATCH";
			$errorCodes[76]="KDC_ERR_KDC_NAME_MISMATCH";

		## @g_kerberos_msgTypes=(); defined as global.
			$g_kerberos_msgTypes[10]="KRB_AS_REQ";
			$g_kerberos_msgTypes[11]="KRB_AS_REP";
			$g_kerberos_msgTypes[12]="KRB_TGS_REQ";
			$g_kerberos_msgTypes[13]="KRB_TGS_REP";
			$g_kerberos_msgTypes[14]="KRB_AP_REQ";
			$g_kerberos_msgTypes[15]="KRB_AP_REP";
			$g_kerberos_msgTypes[20]="KRB_SAFE";
			$g_kerberos_msgTypes[21]="KRB_PRIV";
			$g_kerberos_msgTypes[22]="KRB_CRED";
			$g_kerberos_msgTypes[30]="KRB_ERROR";

		my @encryptionTypes=();
			$encryptionTypes[0]="NULL";
			$encryptionTypes[1]="des-cbc-crc";
			$encryptionTypes[2]="des-cbc-md4";
			$encryptionTypes[3]="des-cbc-md5";
			$encryptionTypes[23]="rc4-hmac";
			$encryptionTypes[24]="rc4-hmac-exp";

		my @paDataTypes=();
			$paDataTypes[1]="PA-TGS-REQ";
			$paDataTypes[2]="PA-ENC-TIMESTAMP";
			$paDataTypes[3]="PA-PW-SALT";
			$paDataTypes[19]="PA-ETYPE-INFO";
			$paDataTypes[128]="PA-PAC-REQUEST";

		my @addrNameTypes=();
			$addrNameTypes[0]="Unknown";
			$addrNameTypes[1]="Principal";
			$addrNameTypes[2]="Service and Instance";
			$addrNameTypes[3]="Serivce and Host";
			$addrNameTypes[4]="Service with host";
			$addrNameTypes[5]="Unique Id";
			$addrNameTypes[6]="X500-Principal";
			$addrNameTypes[7]="SMTP-NAME";
			$addrNameTypes[10]="ENTERPRISE";

		my @hostAddrNameTypes=();
			$hostAddrNameTypes[2]="IPv4";
			$hostAddrNameTypes[3]="Directional";
			$hostAddrNameTypes[5]="ChaosNet";
			$hostAddrNameTypes[6]="XNS";
			$hostAddrNameTypes[7]="ISO";
			$hostAddrNameTypes[12]="DECNET Phase IV";
			$hostAddrNameTypes[16]="APPLETALK-DDP";
			$hostAddrNameTypes[20]="NETBIOS";
			$hostAddrNameTypes[24]="IPv6";

		sub asnElementName{return 0;};
		sub asnElementClassId{return 1;};
		sub asnElementDataType{return 2;};
		sub asnElementInfo{return 3;};
		sub asnElementOptions{return 4;};
		sub asnElementEnumeratation{return 5;};

		sub constructElement { return \@_; }
		sub constructStructure { return \@_; }

		## Element is used to describe each entry (member) in a asn.1 sequence
		## the following are the fields used
		## 0)   asnElementName()                Name field used for display
		#					the name field may be null.
		## 1)   asnElementClassId()             Classid  of the asn.1 command (low 5 bits) range 0-30
		## 2)   asnElementDataType()		Primitive DataType (to be used of validate)
		##					undefined (undef) If sequence of or set of
		## 3)   asnElementInfo()		Structure Name / Sequence name if previous item is undefined
		## 4)   asnElementOptions()		Extra information, "OPTIONAL" , "XXXX" , or  "OPTIONAL XXXX" ,....
		## 5)   asnElementEnumeratations()	Pointer to an Array of names to be used to look up.
		## 					Used only when the dataType is integer or enumerations

		## Structure (of a sequence) contains pointers to the elements in the sequence.
		##  element 1
		##  element 2
		##  ....
		##  element N
		#
		
		## Asn.1 syntax maps into this structure,
		## but not on a 1 to 1 basis. 
		## This structure is used for display/reading only. (Not used for sending/formating asn1 syntax.
		## each sequence must have its own definition
		## a sequence of a TYPE will have a structure with one element describing that TYPE.
		##    This implies creating an implicit structure that contains one element contain TYPE
		#
		#Example SEQUENCE OF PA-DATA OPTIONAL
		#KDC-REQ ::=        SEQUENCE {
           	#	pvno[1]               INTEGER,
           	#	msg-type[2]           INTEGER,
           	#	padata[3]             SEQUENCE OF PA-DATA OPTIONAL,
           	#	req-body[4]           KDC-REQ-BODY }
		#so is used as
		#KDC-REQ ::=        SEQUENCE {
           	#	pvno[1]               INTEGER,
           	#	msg-type[2]           INTEGER,
           	#	padata[3]             SEQ-PA-DATA OPTIONAL,
           	#	req-body[4]           KDC-REQ-BODY }
		#SEQ-PA-DATA ::=        SEQUENCE {
           	#	name             PA-DATA }
		#
		#
		## a sequence of ANY (SEQUENCE) will have a structure describing each element in that sequence.
		#	
		#
		#The asn.1 syntax has an "alias" for a type
		#ALIAS ::=  TYPE
		#This is not supported. Where ever ALIAS is used, it must be replaced by TYPE.
		#example Realm is defined as
		#Realm ::= generalString
		#it is used in Ticket
		#Ticket ::=   [APPLICATION 1] SEQUENCE {
                #             tkt-vno[0]                   INTEGER,
                #             realm[1]                     Realm,
                #             sname[2]                     PrincipalName,
                #             enc-part[3]                  EncryptedData }
		#
		#so is used as
		#Ticket ::=   [APPLICATION 1] SEQUENCE {
                #             tkt-vno[0]                   INTEGER,
                #             realm[1]                     generalString,
                #             sname[2]                     PrincipalName,
                #             enc-part[3]                  EncryptedData }


		# ApplicationDefinition is an hash(associative) array of structure/sequence defintions.
		# each applcation can have its own set of structures/ sequence definitions
		# for kerberos the structures are defined in rfc1510. 
		# and are partialliy encoded as follows.
		#
		# Application level name defintions are  "A:" followed by the classid - a number from 0-30.
		# A:1 is   [Application 1]
		# Application level structure are used in Kerberos to 
		# identify which message is being sent.
		# In Kerberos It is used once in a message.

		$g_pKerberosApplicationDefinition{"A:10"}=
			constructStructure(
				constructElement("Version",1,asnInteger(),0,"") ,
				constructElement("MsgType",2,asnInteger(),0,"",\@g_kerberos_msgTypes),
				constructElement("Pre-Authentication", 3,undef,"PA-DATA","OPTIONAL") ,
				constructElement("Request",4,undef,"KDC-REQ-BODY","")
			);

		$g_pKerberosApplicationDefinition{"A:12"}= $g_pKerberosApplicationDefinition{"A:10"};

		$g_pKerberosApplicationDefinition{"PA-DATA"}=
			constructStructure(
				constructElement("Type",1,asnInteger(),0,"",\@paDataTypes) ,
				constructElement("Value",2,asnOctetString(),"",""),
			);

		$g_pKerberosApplicationDefinition{"KDC-REQ-BODY"}=
			constructStructure(
				constructElement("KDC-OPTIONS",0,asnBitString(),0,"") ,
				constructElement("ClientName",1,undef,"PrincipalName","OPTIONAL"),
				constructElement("Realm",2,asnGeneralString(),"",""),
				constructElement("ServerName",3,undef,"PrincipalName","OPTIONAL"),
				constructElement("From",4,asnGeneralzedTime(),"","OPTIONAL"),
				constructElement("EndTime",5,asnGeneralzedTime(),"",""),
				constructElement("RenewableUntil",6,asnGeneralzedTime(),"","OPTIONAL"),
				constructElement("Nonce(RandomNumber)",7,asnInteger(),"",""),
				constructElement("EncryptionTypes",8,undef,"SetOfencryptionTypes",""),
				constructElement("addresses",9,undef,"HostAddress","OPTIONAL"),
				constructElement("authentication-data",10,undef,"EncryptedData","OPTIONAL"),
				constructElement("additional-tickets",11,undef,"Tickets","OPTIONAL"),
			);

		$g_pKerberosApplicationDefinition{"KDC-REP"}=
			constructStructure(
				constructElement("Version",0,asnInteger(),0,"") ,
				constructElement("MsgType",1,asnInteger(),0,"",\@g_kerberos_msgTypes),
				constructElement("Pre-Authentication", 2,undef,"PA-DATA","OPTIONAL") ,
				constructElement("ClientRealm",3,asnGeneralString(),"",""),
				constructElement("ClientName",4,undef,"PrincipalName",""),
				constructElement("Tickets",5,undef,"Tickets",""),
				constructElement("enc-part",6,undef,"EncryptedData","OPTIONAL"),
			);

		$g_pKerberosApplicationDefinition{"A:11"}=$g_pKerberosApplicationDefinition{"KDC-REP"};
		$g_pKerberosApplicationDefinition{"A:13"}=$g_pKerberosApplicationDefinition{"KDC-REP"};

		$g_pKerberosApplicationDefinition{"SetOfencryptionTypes"}=
			constructStructure(
				constructElement("",1,asnInteger(),0,"",\@encryptionTypes) ,
			);

		$g_pKerberosApplicationDefinition{"SetOfGeneralStrings"}=
			constructStructure(
				constructElement("",1,asnGeneralString(),0,"") ,
			);

		$g_pKerberosApplicationDefinition{"HostAddress"}=
			constructStructure(
				constructElement("addr-type",0,asnInteger(),0,"",\@hostAddrNameTypes) ,
				constructElement("address",1,asnGeneralString(),0,"") ,
			);

		$g_pKerberosApplicationDefinition{"EncryptedData"}=
			constructStructure(
				constructElement("EncryptionType",0,asnInteger(),0,"",\@encryptionTypes) ,
				constructElement("KeyVersion",1,asnInteger(),0,"OPTIONAL") ,
				constructElement("ciper",2,asnOctetString(),0,"") ,
			);

		$g_pKerberosApplicationDefinition{"Tickets"}=
			constructStructure(
				constructElement("TicketVersion",0,asnInteger(),0,"") ,
				constructElement("Realm",1,asnGeneralString(),"",""),
				constructElement("ServerName",2,undef,"PrincipalName",""),
				constructElement("enc-part",3,undef,"EncryptedData",""),
			);

		$g_pKerberosApplicationDefinition{"A:1"}=$g_pKerberosApplicationDefinition{"Tickets"};

		$g_pKerberosApplicationDefinition{"PrincipalName"}=
			constructStructure(
				constructElement("name-type",0,asnInteger(),0,"",\@addrNameTypes) ,
				constructElement("",1,undef,"SetOfGeneralStrings","") ,
			);

		$g_pKerberosApplicationDefinition{"A:30"}=
			constructStructure(
				constructElement("Version",0,asnInteger(),0,"") ,
				constructElement("MsgType",1,asnInteger(),0,"",\@g_kerberos_msgTypes),
				constructElement("ctime",2,asnGeneralzedTime(),"","OPTIONAL"),
				constructElement("cusec",3,asnInteger(),0,"") ,
				constructElement("stime",4,asnGeneralzedTime(),"",""),
				constructElement("susec",5,asnInteger(),0,"") ,
				constructElement("error-code",6,asnInteger(),0,"",\@errorCodes) ,
				constructElement("crealm",7,asnGeneralString(),"","OPTIONAL"),
				constructElement("cname",8,undef,"PrincipalName","OPTIONAL"),
				constructElement("realm",9,asnGeneralString(),"",""),
				constructElement("ServerName",10,undef,"PrincipalName",""),
				constructElement("e-text",11,asnGeneralString(),0,"OPTIONAL") ,
				constructElement("e-data",12,asnOctetString(),0,"OPTIONAL") ,
			);

		@asn_decode_simple_array = (
		  \&asn_decode_error,
		  ## 1
		  \&asn_decode_boolean,
		  \&asn_decode_integer,
		  \&asn_decode_bitstring,
		  \&asn_decode_octetstring,
		  \&asn_decode_null,
		  ## 6
		  \&asn_decode_object_id,
		  \&asn_decode_object_desc,
		  \&asn_decode_external,
		  \&asn_decode_real,
		  \&asn_decode_enumerated,
		  ## 11
		  \&asn_decode_unknown,
		  \&asn_decode_future,
		  \&asn_decode_future,
		  \&asn_decode_future,
		  \&asn_decode_future,
		  ## 16
		  \&asn_decode_sequence,
		  \&asn_decode_set,
		# 18-22 , 25-27 character strings
		# 28,29,30 reserved for future
		  \&asn_decode_unimplemented,
		  \&asn_decode_unimplemented,
		  \&asn_decode_unimplemented,
		  ## 21
		  \&asn_decode_unimplemented,
		  \&asn_decode_unimplemented,
		  \&asn_decode_time,
		  \&asn_decode_time,
		  \&asn_decode_unimplemented,
		  ## 26
		  \&asn_decode_unimplemented,
		  \&asn_decode_charstring,
		  \&asn_decode_future,
		  \&asn_decode_future,
		  \&asn_decode_future,
		  \&asn_decode_future
		);

	}
}

sub handleKerberosPkt{
	my($pPktInfo)=@_;
	## die "TESTED\n";
	$$pPktInfo{msgtype}="KERBEROS";
	if ($g_kerberos<=0) {
		$g_filtered_packets++;
		$a="Kerberos Packets   using option -kerberos to enable tracing of kerberos packets";
		$g_filter_cause{"$a"}++;
	} else {
		my $kerberosMsgType= 0x1f&unpack("C",substr($$pPktInfo{pkt}, $$pPktInfo{msg_offset}, 1));
		my $kerMsgDesc="Ker??";

		## print "FRAME:$$pPktInfo{frame},$$pPktInfo{msg_offset},$$pPktInfo{msg_len}:\n";
		my $depth=0;
		my @pContext=();

		$kerMsgDesc=$g_kerberos_msgTypes[$kerberosMsgType];
		if (!defined($kerMsgDesc)) {$kerMsgDesc="KER_$kerberosMsgType";};
		$$pPktInfo{sipmsg}="$kerMsgDesc\n";

		my ($output,$dummy)=
			tokenizeBerString( 
				substr($$pPktInfo{pkt}, $$pPktInfo{msg_offset}, $$pPktInfo{msg_len} ) ,
				\$depth,
				\%g_pKerberosApplicationDefinition,
				\@pContext,
				1);
		$$pPktInfo{sipmsg} .= $output;
		$$pPktInfo{event}="extraprotocol $kerMsgDesc";
		addFrameToList($pPktInfo);
	}
	## die "DID IT ONCE SO QUIT\n";
	## print "\n\n";
}

## Define Constants
sub asnBool		{ return 0x01 ;};
sub asnInteger		{ return 0x02 ;};
sub asnBitString	{ return 0x03 ;};
sub asnOctetString	{ return 0x04 ;};
sub asnSequenceOf	{ return 0x10 ;};
sub asnGeneralString	{ return 0x18 ;};
sub asnGeneralzedTime	{ return 0x1b ;};

sub parseAsn1ClassAndLenFields {
	my ($offset,$string,$length)=@_;
	my ($constructor,$class,$classid,$len)= (0,0,0,0);
	if ($$offset+2>$length) {
		## Error - no more data.
		$$offset=$length;
	} else {
		($classid,$len)= unpack("CC",substr($$string,$$offset,2));
		$$offset +=2;
	}
	if ($len>=128) {
		my $lenOfLen=$len&0x7f;
		$len=0;
		if ($$offset+$lenOfLen>$length) {
			## Error - no more data.
			$$offset=$length;
		} else {
			my $index;
			for ($index=0;$index<$lenOfLen;$index++) {
				$len <<= 8;
				$len|=unpack("C",substr($$string,$$offset+$index,1));
			}
			$$offset+=$lenOfLen;
		}
	}

	$constructor=0;
	$constructor = 1 if ($classid &0x20);

	$class = "Universal";
	$class = "Application" if (($classid &0xc0)==0x40);
	$class = "Context" if (($classid &0xc0)==0x80);
	$class = "Private" if (($classid &0xc0)==0xc0);

	## classid the the low order 5 bits.
	$classid &= 0x1f;

	return ($class,$constructor,$classid,$len);
}


## tokenize a ber encoded (asn.1) buffer.
## constructs an array of tokens where a token consists of
## TokenId, len, info
## berlen is has two forms short and long
#        Short berlen is 0-127
#        	examples 5 ;  11		:lengths 5,11
#        long is  0x80|lenOfLenthField , high order Len, .... low order len
#        	examples 0x81,5   ; 0x81,11    ; 0x82,0,5 ; 0x82,0,11
sub tokenizeBerString {
	my ($string,$depth,$pApplicationDefinition,$pContext,$seqInContext)=@_;
	my (@tokens)=();
	my $length=length($string);
	my $offset=0;
	my ($tail,$hdr,$result,$classid,$len,$index,$class,$constructor);
	my $prefix=substr("                               ",0,2*$$depth);
	my $output="";
	my %dummyContext=();
	my $seq=$seqInContext;
	my $contextid=0;
	my $nextoffset=0;
	my $incdepth=0;
	my $pElement=0;
	my $pNextContext=$pContext;
	my $nextseq=0;
	$seq--;
	while ($offset<$length) {
		$seq++;
		$pNextContext=$pContext;

		($class,$constructor,$classid,$len)= parseAsn1ClassAndLenFields(\$offset,\$string,$length);
		($pElement,$seq)=findElement($pContext,$seq,$class,$constructor,$classid);
		$nextseq=$seq;

		# find the next element in the context.
		# rules 1) if this is a context then context change the sequence number to its appropiate
		# 	location in the structure. Allows optional elemnet to be skipped.
		# rules 2) if the sequence number is past the max in the structure then assume
		# 	this is an array of the structure. so reset the sequence numebr to 1 an start over again.
		sub findElement {
			my ($pContext,$seq,$class,$constructor,$classid) = @_;
			my $pElement;
			## Get max item in sequence/structure.
			my $maxSeq=1+$#{$pContext};
			## If sequence number is past max then start over at begining.
			if ( ($seq>$maxSeq) && ($maxSeq>0) ){ 
				## print "reseting seq($seq) to one max=$maxSeq $class:classid=$classid\n";
				$seq=1;
			};
			## handles sequence of TYPE.
			if ($class eq "Context" ) {
				## find appropiate entry in the sequence/structure 
				my ($index,$expected_seq,$expected_classid);
				my $seqok=0;
				if ( ($seq>0) && ($seq<=$maxSeq) ){ 
					$pElement=@{$pContext}[$seq-1];
					$expected_classid=$$pElement[asnElementClassId()];
					if ($classid == $expected_classid) {
						$seqok=1;
					} else {
					}
				}
				## print "seq($seq) seqok=$seqok max=$maxSeq $class:classid=$classid\n";
				for ($index=1;(($index<=$maxSeq)&&($seqok==0));$index++) {
					$pElement=@{$pContext}[$index-1];
					$expected_classid=$$pElement[asnElementClassId()];
					## print "seq($seq) $index $expected_classid seqok=$seqok max=$maxSeq $class:classid=$classid\n";
					if ($expected_classid==$classid) {
						## print "reseting seq. $seq=>$index element=@{$pElement} $class:classid=$classid\n";
						$seq=$index;
						$seqok=1;
					}
				}
			} else {
			}
			$pElement=$$pContext[$seq-1];
			return ($pElement,$seq);
		}

		## compute  next offset and verify len 
		$nextoffset=$offset+$len;
		if ($nextoffset>$length) {
			print "DEBUG if $nextoffset>$offset) $length\n";
			## Error - no more data.
			$offset=$length;
			$output .= "\nPARSING LEN ERROR\n";
			$seq=-1;
			next;
		}
		my $classChr=substr($class,0,1);
		if (0) {
			if (!defined $pContext) {
				my @p=();
				$pContext=\@p;
			}
			printf "depth=$$depth seq=$seq l=$len $classChr:classid=%-2d context<$pContext> ",$classid;
			#print "@{$pContext} ";
			print "$$pElement[0] ";
			print "$$pElement[1] ";
			print "$$pElement[2] ";
			print "$$pElement[3] ";
			print "$$pElement[4] ";
			print "\n";
		}

		if ($class eq  "Universal" ) {
			## decode asn primitive
			## return hdr == ERROR if critical error.
			($result,$hdr,$tail)=asn_decode(substr($string,$offset,$len),$len,$classid,$constructor,$pElement);
			if (0 && ($hdr eq "ERROR" ) ) {
				$output .= "\n$tail\n";
				$offset=$length;
				$seq=-1;
				next;
			}

			if ($classid==16) {
				$incdepth=1;
			} elsif ($classid==17) {
			} else {
			}
			## Have a displayable output so write it out
			if ($result ne "" ) {
				my $newhdr = $$pElement[asnElementName()];
				if ( defined($newhdr) ) { 
					$hdr=$newhdr;
				}
				$newhdr = $prefix.$hdr."  ".$result." $tail\n";;
				$output .= $newhdr;
				## print $newhdr;
			}
		} elsif ($class  eq "Application") {
			my $err="";
			my $name = "A:$classid";;
			if (!($constructor) ) {
				my $err="\nLOGIC ERROR:PARSING IMPLEMENTATION WARNING Application must be constructor. \n";
				$output .= $err;
				## print $err;
			}
			$err="";
			$pNextContext=$$pApplicationDefinition{$name};
			$nextseq=1;
			if (!defined $pNextContext) {
				$err="APPLICATION:NO FormatDefinition for $name";
				my @p=();
				$pNextContext= \@p;
				$output .= $err;
				## print $err;
			}
		} elsif ($class eq "Context" ) {
			if ( (!defined($$pElement[asnElementDataType()])) && (defined($$pElement[asnElementName()]) ) ) {
				## print "$$pElement[asnElementName()]\n";
				my $err="";
				if (!($constructor) ) {
					$err="\nLOGIC ERROR:PARSING IMPLEMENTATION WARNING Context should be constructor. \n";
					$output .= $err;
					## print $err;
				}  
				## $$pElement[asnElementDataType()]=".";
				my $name=$$pElement[asnElementInfo()];
				## print "Context Change $name \n";
				$pNextContext= $$pApplicationDefinition{$name};
				if (!defined $pNextContext) {
					$err=" (NO FormatDefinition for $name)";
					my @p=();
					$pNextContext= \@p;
				}
				$name = "$$pElement[asnElementName()]";
				if (!($name =~/^\s*$/)) {
					$name = "${prefix}$name  $err\n";;
					$output .= $name;
					## print $name;
				}
				$nextseq=1;
			} else {
				## print "GOT HERE\n";
			}
		} else { ## $class eq "Private" 
		}
		
		if ($constructor) {
			## have a constructed string so parse it
			my ($noutput,$nseq)=("",0);
			$$depth += $incdepth;
			($noutput,$nseq) = tokenizeBerString(substr($string,$offset,$len),$depth,$pApplicationDefinition,$pNextContext,$nextseq);
			$pNextContext=$pContext;
			if ($noutput ne "") {
				$output .=$noutput;
			}
			$$depth -= $incdepth;
			$incdepth =0;
			## printf("$$depth:$seq:$nseq:$noutput\n");
			if ( 0 && ($nseq<0) ) {
				## have an error from the previous tokenizer. Stop this packets
				$seq=-1;
				$offset=$length;
				next;
			}
		} 
		## print "$seq:$classid: $output\n";
		$offset=$nextoffset;
	}
	return ($output,$seq);
}

sub asn_decode  {
	my ($string,$len,$classid,$constructor,$pElement)=@_;
	my ($result,$hdr,$tail)=&{$asn_decode_simple_array[$classid]}($string,$len,$classid,$constructor,$pElement);
	my $limit=64;
	my $tmplen=length($result);
	if ($tmplen>$limit) {
		$result=substr($result,0,$limit);
		$tail.=" (len=$tmplen. truncated to $limit)";
	}
	if ($result =~ /\s/) { 
		## if white space add quotes
		$result = "\"".$result."\"";
	}
	return ($result,$hdr,$tail);
}

sub asn_decode_integer_sub {
	my ($param,$len,$classid,$constructor,$pElement)=@_;
	my ($index,$tmp,$width);
	my $value=0;
	my $hdr="int";
	my $minusflag=0;
	my $hex=unpack(sprintf("H%d",2*$len),substr($param,0,$len));
	$value=0; 
	$width=0xff;
	if ($len>0) {
		## get first value and check if negative number. Set flag if negative
		$value=unpack("C",substr($param,0,1));
		if ( ($value>=128) ) {
			$minusflag=1;
		}
		## find mask of width 
		## extract value.
		for ($index=1;$index<$len;$index++) {
			$tmp=unpack("C",substr($param,$index,1));
			## detected a negative number in 2-complement format
			$width <<=8;
			$width |= 0xff;
			$value <<=8;
			$value|=$tmp;
		}
		## if negative value. must make it looked signed.
		## Complement value and add one.    makes the negative number postive.
		## Now make the number negative using now arithmetic.
		if ($minusflag) {
			$value ^= $width;
			$value +=1;
			$value =  - $value;
		}
	}
	## print "int=$value\n";
	return ($value,$hdr,"(0x$hex)");
}

sub asn_decode_integer {
	my ($param,$len,$classid,$constructor,$pElement)=@_;
	my ($value,$hdr,$tail)= asn_decode_integer_sub($param,$len,$classid,$constructor,$pElement); 
	my $enum="";
	## find value in enum table - if defined.
	if (defined ($pElement) ) {
		my $enums = $$pElement[asnElementEnumeratation{}];
		if (defined($enums)) {
			$enum=$$enums[$value];
			if (defined($enum)) {
				return ($enum,"enum",$tail);
			}
		}
	}
	return ($value,$hdr,$tail);
}


sub asn_decode_boolean {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asm_decode_integer_sub($param,$len);
	$result = ($result==0)?"false":"true";
	return ($result,"bool","");
}

sub asn_decode_bitstring {
	## Note that the first byte of the bit stream is the number of bits that are padded.
	my ($param,$len)=@_;
	my ($tail,$index,$tmp,$pad);
	my $value="";
	$pad=unpack("C",substr($param,0,1));
	for ($index=1;$index<$len;$index++) {
		$tmp=unpack("C",substr($param,$index,1));
		$value.=sprintf("%02x",$tmp);
	}
	$tail="";
	if ($pad!=0) {
		$tail =  "($pad pad bits at end of string)";
	}
	## print "octetstr=$value\n";
	return ($value,"bitstr",$tail);
}

sub asn_decode_octetstring {
	my ($param,$len)=@_;
	my ($index,$tmp);
	my $printable=1;
	my $hex=unpack(sprintf("H%d",2*$len),substr($param,0,$len));
	for ($index=0;$index<$len;$index++) {
		$tmp=unpack("C",substr($param,$index,1));
		if ( ($tmp <0x20) || ($tmp>0x7e) )  {
			$printable=0;
		}
	}
	if ($printable == 1) {
		return asn_decode_charstring($param,$len);
	}
	## print "octetstr=$value\n";
	return ($hex,"octetstr","");
}

sub asn_decode_time {
	my ($param,$len)=@_;
	my ($index,$tmp);
	my($year,$mon,$day,$hour,$min,$sec,$zone)=unpack("A4A2A2A2A2A2A",$param);
	my $value="$year-$mon-$day $hour:$min:$sec ($zone)";
	## print "charstr=$value\n";
	return ($value,"time","");
}

sub asn_decode_charstring {
	my ($param,$len)=@_;
	my ($index,$tmp);
	my $value="";
	for ($index=0;$index<$len;$index++) {
		$tmp=unpack("C",substr($param,$index,1));
		$value.=sprintf("%c",$tmp);
	}
	## print "charstr=$value\n";
	return ($value,"char","");
}

sub asn_decode_error {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"null","");
}

sub asn_decode_null {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"null","");
}
sub asn_decode_object_id {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"oid","");
}
sub asn_decode_object_desc {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"object desc","");
}
sub asn_decode_external {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"external","");
}
sub asn_decode_real {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"real","");
}
sub asn_decode_enumerated {
	my ($param,$len,$classid,$constructor,$pElement)=@_;
	return asn_decode_integer($param,$len,$classid,$constructor,$pElement); 
}
sub asn_decode_unknown {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"unknown","");
}
sub asn_decode_future {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"future","");
}
sub asn_decode_sequence {
	my ($param,$len,$classid,$constructor)=@_;
	my ($hdr,$tail)=("","");
	if (!($constructor)) {
		## Error - 
		## must have constructor set.
		$tail = "\nPARSING ERROR:\"Sequeunce of\" no constructor bit set\n";
		$hdr="ERROR";
	}
	return ("",$hdr,$tail);
}

sub asn_decode_set {
	my ($param,$len,$classid,$constructor)=@_;
	my ($hdr,$tail)=("","");
	if (!($constructor)) {
		## Error - 
		## must have constructor set.
		$tail = "\nPARSING ERROR:\"Set of\" no constructor bit set\n";
		$hdr="ERROR";
	} else {
		$tail = "\n\"Set of\" not implemented\n";
		$hdr="ERROR";
	}
	return ("",$hdr,$tail);
}

sub asn_decode_unimplemented {
	my ($param,$len)=@_;
	my($result,$hdr,$tail)=asn_decode_octetstring($param,$len);
	return ($result,"unimplemented","");
}

sub process_symmetric_upd_port_detection {
	my ($pPktInfo)=@_;
	if ($$pPktInfo{ipprotocol}== 6) {
		## tcp
		if (!(defined $g_symmetric_udp_port_detection{"$$pPktInfo{srcip}" } ) ) {
			$g_symmetric_udp_port_detection{"$$pPktInfo{srcip}" }="tcp"
		}
		if (!(defined $g_symmetric_udp_port_detection{"$$pPktInfo{dstip}" } ) ) {
			$g_symmetric_udp_port_detection{"$$pPktInfo{dstip}" }="tcp"
		}
	} elsif ($$pPktInfo{ipprotocol}== 17) {
		## udp
		my $flag=0;
		if (!(defined $g_symmetric_udp_port_detection{"$$pPktInfo{srcip}" } ) ) {
			$flag=$g_symmetric_udp_port_detection{"$$pPktInfo{srcip}:$$pPktInfo{srcport}" };
			if (!defined($flag)) { $flag=0; };
			$flag |=1;	## set src flag
			$g_symmetric_udp_port_detection{"$$pPktInfo{srcip}:$$pPktInfo{srcport}" } = $flag ;
		}
		if (!(defined $g_symmetric_udp_port_detection{"$$pPktInfo{dstip}" } ) ) {
			$flag=$g_symmetric_udp_port_detection{"$$pPktInfo{dstip}:$$pPktInfo{dstport}" };
			if (! defined($flag)) { $flag=0; };
			$flag |=2;	## set dst flag
			$g_symmetric_udp_port_detection{"$$pPktInfo{dstip}:$$pPktInfo{dstport}" } =$flag;
		}
	}
}

sub processes_symmetric_udp_port_information {
	my (%qty,%keys,$ip,$port,$key,$value,$defined,$qty);
	## Check for any TX only Port,
	while (($key,$value) = each %g_symmetric_udp_port_detection) {
		if ($key =~ /^(\d+[.]\d+[.]\d+[.]\d+)[:](\d+)$/ ) {
			$ip=$1; $port=$2;
			## found a udp ip/port pair
			## Check if there was a tcp packet
			## Then check if tx only port. 
			## print STDERR __LINE__." DEBUG QWERTY $key,$ip,$value\n";
			
			$defined =$g_symmetric_udp_port_detection{"$ip"};  ## check for tcp
			if (defined $defined ) {
				;
			} elsif ($value==1) {
				## transmit only on a port
				$g_symmetric_udp_port_detection{"$ip"}="tx only on port";  ## check for tcp
			}
		}
	}
	return if ($g_singleua==0); ## symmetric UDP port detection turned off.
	## Now look for port rx or rx/tx 
	while (($key,$value) = each %g_symmetric_udp_port_detection) {
		if ($key =~ /^(\d+[.]\d+[.]\d+[.]\d+)[:](\d+)$/ ) {
			$ip=$1; $port=$2;
			## found a udp ip/port pair
			## Check if there was a tcp packet or tx only on port
			## print STDERR __LINE__." DEBUG QWERTY $key,$ip,$value\n";
			
			$defined =$g_symmetric_udp_port_detection{"$ip"};  ## check for tcp or tx only on a port
			if (defined $defined ) {
				;
			} else {
				#print __LINE__." $key $value\n";
				$g_symmetric_udp_port{"$key"}=$value;
				parseIpAddr($key);
				if (! defined $qty{$ip} ) {
					$qty{$ip}=1;
				} else {
					$qty{$ip}+=1;
				}
			}
		}
	}
	undef  %g_symmetric_udp_port_detection;
	while (($key,$value) = each %g_symmetric_udp_port) {
		if ($key =~ /^(\d+[.]\d+[.]\d+[.]\d+)[:](\d+)$/ ) {
			$ip=$1; $port=$2;
			## found a udp ip/port pair
			## Check if there was a tcp packet
			## Then check if tx/rx packet.
			# my $alias1=$g_alias_by_ip_addr{$ip};
			# my $alias2=$g_alias_by_ip_addr{$key};
			## print STDERR __LINE__." DEBUG $qty{$ip},$key,$ip,$port\n";
			if ($qty{$ip}==1) {
				parseIpAddr($key);
				$g_symmeteric_udp_port__single_port_per_ip_addr{$ip}=$port;
				## print STDERR __LINE__." DEBUGAZXS $qty{$ip},$key,$ip,$port\n";
				## print STDERR __LINE__." DEBUG QWERTY $key,$ip,$value\n";
			} elsif ($qty{$ip}> 1) {
				## print STDERR __LINE__." DEBUG QWERTY $key,$ip,$value\n";
				parseIpAddr($key);
			} else {
				## print STDERR __LINE__." DEBUG QWERTY $key,$ip,$value\n";
				$g_symmetric_udp_port{"$key"}="";
				delete $g_symmetric_udp_port{"$key"};
			}
		} else {
print STDERR "
** INTERNAL LOGIC ERROR: symmeteric udp port detection.
Please send your capture file to ray.elliott\@ipc.com with a brief description of how you are using this program
\n";
		}
	}
}


