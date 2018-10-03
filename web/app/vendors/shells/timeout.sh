#!/bin/sh
# 2008 by Ortwin Glueck


DATABASE_HOST=192.168.1.115
DATABASE_USER=root
DATABASE_NAME=exchange

function after_grep(){
#cd /opt/lampp/htdocs/exchange/app/tmp/capture_files/ 
#perl /opt/lampp/htdocs/exchange/app/tmp/capture_files/sip_scenario.pl /opt/lampp/htdocs/exchange/app/tmp/capture_files/1293456449_pcap.pcap
	CAPTURE_FILE_SIZE=`stat -c %s ${2}`
	psql -h ${DATABASE_HOST} -U ${DATABASE_USER} -d ${DATABASE_NAME} -c "UPDATE capture SET time_val = extract(epoch from now())::bigint - extract(epoch from capture_time)::bigint,pid=0,file_size=${CAPTURE_FILE_SIZE} WHERE capture_id = ${1}"
	exit
}
function before_grep(){
#cd /opt/lampp/htdocs/exchange/app/tmp/capture_files/ 
#perl /opt/lampp/htdocs/exchange/app/tmp/capture_files/sip_scenario.pl /opt/lampp/htdocs/exchange/app/tmp/capture_files/1293456449_pcap.pcap 
	psql -h ${DATABASE_HOST} -U ${DATABASE_USER} -d ${DATABASE_NAME} -c "UPDATE capture SET capture_time = now(),pid = ${2},time_val=0 WHERE capture_id = ${1}"
}
if [ $# -lt 2 ]; then
	echo "Starts a program and kills it if it is still alive"
	echo "after a given time."
	echo "Syntax: timeout [-signal] timespec program [arguments]"
	echo "  signal    the signal to send to the process, default is 9 (kill argument)"
	echo "  timespec  time in seconds (sleep argument)"
	echo "  program   the program to execute"
	echo "  arguments the arguments for program"
	echo "The exit code is preserved or 127 if it could not be determined"
	exit 1
fi
if [ "${1:0:1}" = "-" ]; then
	SIGNAL="${1}"
	shift
else
	SIGNAL="-9"
fi

TIME=${1}
shift
CAPTURE_ID=${1}
shift
CAPTURE_FILE=${1}
shift
#echo $CAPTURE_ID
#echo $TIME
# start program in the background
$@ >/dev/null 2>/dev/null &
PID=$!
echo $PID
if [ "${PID}" = "0" ]; then
# process has already ended
	exit 127;
fi
before_grep $CAPTURE_ID $PID
for((i=1;i<=$TIME;i++))
do
#(sleep "${TIME}";  sudo kill "${SIGNAL}" "${PID}") & 2>/dev/null
if test -e /proc/$PID; then
	sleep 1 2>/dev/null
else
	after_grep $CAPTURE_ID $CAPTURE_FILE
fi
done
kill "${SIGNAL}" "${PID}" & 2>/dev/null
after_grep $CAPTURE_ID $CAPTURE_FILE
KILLER=$!
wait "${PID}" 2>/dev/null
R=$?
kill -HUP "${KILLER}" 2>/dev/null
exit ${R}

