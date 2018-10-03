#!/usr/bin/env python3
#krasytod fix 2017
import argparse
import io
from configparser import RawConfigParser
import time
import datetime
import os
import os.path
import stat
import subprocess
import base64
import uuid

import psycopg2
import psycopg2.extras

import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText


def write_to_file(text):
	try:
		with open('args.txt', 'a') as out:
			out.write(str(args))
	except Exception as e:
		pass

def load_config(config_file_path):
	ini_str = open(config_file_path, 'r').read()
	ini_fp  = io.StringIO(ini_str)
	config = RawConfigParser(strict=False, allow_no_value=True)
	config.readfp(ini_fp)
	return config


def parse_args():
	parser = argparse.ArgumentParser(description="CDR Export")
	parser.add_argument('-c', '--config', required=True,
						dest="config", help="Config File")
	parser.add_argument('-i', '--log', required=True, type=int,
						dest='log_id', help='Log ID')
	args = parser.parse_args()
	return args


def export_cdr(log_id, config,args = ""):
	error_flg = False
	conn = psycopg2.connect(host=config.get('db','hostaddr'),
								port=config.get('db','port'),
								database=config.get('db','dbname'),
								user=config.get('db','user'),
								password=config.get('db','password'))
	conn.autocommit = True
	cur = conn.cursor(cursor_factory=psycopg2.extras.DictCursor)


	#cur.execute ( "insert into  krasytest  (id,status) VALUES (1,'test') ")

	cur.execute("SELECT * FROM cdr_export_log WHERE id = %s", (log_id, ))
	cdr_export_log = cur.fetchone()

	cur.execute("UPDATE cdr_export_log SET status = 1 WHERE id = %s", (log_id, ))
	#cur.execute("UPDATE cdr_export_log SET where_sql = %s WHERE id = 170", (args))

	export_path = os.path.realpath(os.path.join(os.path.dirname(__file__), os.path.pardir, 'download', 'cdr_download'))
	print ("export_path",export_path)
	if not os.path.exists(export_path):
		os.makedirs(export_path)
	try:
		os.chmod(export_path, stat.S_IRWXO+stat.S_IRWXU+stat.S_IRWXG)
	except:
		print ("chmod 777 failed")
		#write_to_file("chmod 777 failed")


	cdr_start = datetime.datetime.strptime(str(cdr_export_log['cdr_start_time'])[0:19], "%Y-%m-%d %H:%M:%S")
	cdr_end = datetime.datetime.strptime(str(cdr_export_log['cdr_end_time'])[0:19], "%Y-%m-%d %H:%M:%S")

	print ("start %s  end %s" % (cdr_start,cdr_end))
	#total_days = (cdr_end - cdr_start).days
	#cur.execute("UPDATE cdr_export_log SET total_days = %s, file_dir = %s WHERE id = %s", (total_days, export_path, log_id, ))

	cur.execute("select TABLE_NAME as name from INFORMATION_SCHEMA.TABLES where TABLE_NAME like'client_cdr2%' order by TABLE_NAME limit 1")
	table_info = cur.fetchone()
	last_time_name = table_info['name'][10:]
	last_table_time = datetime.datetime.strptime(last_time_name, "%Y%m%d")

	# print (type(last_table_time),last_table_time)
	# print (type(cdr_start),cdr_start)

	if cdr_start < last_table_time:
		cdr_start = last_table_time
	now = datetime.datetime.now()
	if cdr_end > datetime.datetime(now.year,now.month,now.day):
		cdr_end = datetime.datetime(now.year,now.month,now.day)

	print ("start %s  end %s" % (cdr_start,cdr_end))

	this_download_path_name = str(cdr_export_log['id'])+'_'+str(int(time.time()))

	log_file_path_name = os.path.join(export_path, this_download_path_name)
	print("log_file_path_name",log_file_path_name)
	if not os.path.exists(log_file_path_name):
		os.makedirs(log_file_path_name)
	try:
		os.chmod(log_file_path_name, stat.S_IRWXO+stat.S_IRWXU+stat.S_IRWXG)
	except:
		print ("chmod 777 failed")
	#print (log_file_path_name)


	total_row = 0
	total_days = 0 #1
	completed_days = 0
	tempCdrStart = cdr_start

	if cdr_start > cdr_end:
	  cdr_start = cdr_end

	while tempCdrStart <= cdr_end:
		total_days = total_days + 1
		print ("total_days",total_days  )
		tempCdrStart = tempCdrStart + datetime.timedelta(days=1)

	# Set total days value before exporting
	cur.execute("UPDATE cdr_export_log SET status = 2,finished_date = 0, total_days = %s WHERE id = %s", (total_days, log_id, ))

	while cdr_start <= cdr_end:
		print( cdr_start,cdr_end )
		time_str = cdr_start.strftime('%Y%m%d')
		# print ("this time is %s time str %s" % (cdr_start,time_str))
		this_where = cdr_export_log['where_sql'].replace('client_cdr.','client_cdr'+time_str+'.')
		this_show_fields = cdr_export_log['show_fields_sql'].replace('client_cdr.','client_cdr'+time_str+'.')
		# this_show_fields = cdr_export_log['show_fields_sql']
		# sql = this_show_fields
		sql = 'SELECT ' + this_show_fields + ' FROM client_cdr' + time_str + ' WHERE ' + this_where
		#print (sql)

		#分天导入文件
		this_file_name = os.path.join(log_file_path_name, time_str+'.csv')
		copy_sql  = "COPY (%s) TO STDOUT WITH CSV HEADER " % (sql)

		error_flg = False

		# copy_sql = copy_sql.replace('client_cdr','client_cdr%s' % time_str)
		#print(copy_sql)
		try:
			handle = open(this_file_name, "w")
		except:
			error_flg = True
			error_msg = 'Download file path do not have write permissions'
			# cur.execute("UPDATE cdr_export_log SET status = -1 , error_msg = 'Download file path do not have write permissions' WHERE id = %s", (log_id, ))
			break

		error_msg = ''
		# cur.execute(copy_sql)
		try:
			cur.copy_expert(copy_sql,handle)
		except (psycopg2.extensions.QueryCanceledError, psycopg2.OperationalError):
			# print(psycopg2.extensions.QueryCanceledError)
			# print(psycopg2.OperationalError)
			error_flg = True
			error_msg = psycopg2.extensions.QueryCanceledError + "\n" + psycopg2.OperationalError
		except psycopg2.DatabaseError:
			print(psycopg2.DatabaseError)
			error_flg = True
			# error_msg = psycopg2.DatabaseError
			error_msg = psycopg2.DatabaseError
		handle.close()
		if error_flg == True:
			print (error_msg)
			break
		else:
			rows_cmd = "wc -l %s" % (this_file_name)
			rows_result = subprocess.check_output(rows_cmd, shell=True)
			rows = int(rows_result.decode().split( )[0]) - 1
			total_row += rows
			completed_days = completed_days + 1
			print ("completed_days",completed_days ,total_days )

			cur.execute("UPDATE cdr_export_log SET completed_days = %s, finished_date = finished_date + 1 WHERE id = %s", (completed_days,log_id, ))
			#compress
			# cmd = "cat %s | gzip > %s.gz" % (export_file_path, export_file_path)



		cdr_start = cdr_start + datetime.timedelta(days=1)

#python3 /home/opt/webui/script/class4_cdr_export.py -c /home/opt/webui/etc/dnl_softswitch.ini -i 176 > /home/opt/webui/download/cdr_download/cdr_2017-09-20_2017-09-21_1505915186.csv.progress

	# return

	if error_flg == True:
		cur.execute("UPDATE cdr_export_log SET status = -1 , error_msg = %s WHERE id = %s", (error_msg,log_id, ))
		cur.close()
		conn.close()
		return

	cur.execute("UPDATE cdr_export_log SET status = 3, file_rows = %s WHERE id = %s", (total_row,log_id, ))

	os.chdir(export_path)
	#print("export_path",export_path)
	result_file_name = str(log_id)+str(uuid.uuid4())+'.zip'
	#cdr_export_log['file_name'].replace('.csv','.zip').replace('.tar.bz2', '.zip').replace('3zip','.zip')

	#cmd = "tar -jcvf %s %s" % (result_file_name,this_download_path_name)
	cmd = "zip %s %s"% (result_file_name,this_download_path_name+'/*')
	os.system(cmd)

	print (cmd)
	os.system('rm -rf %s' % this_download_path_name)
	cur.execute("UPDATE cdr_export_log SET status = 4 WHERE id = %s", (log_id, ))
	cur.execute("UPDATE cdr_export_log SET finished_date = finished_date + 1, finished_time = CURRENT_TIMESTAMP WHERE id = %s", (log_id, ))

	result_path = os.path.dirname(result_file_name)
	#print (log_file_path_name)
	#write_to_file(log_file_path_name)
	cur.execute("UPDATE cdr_export_log SET file_dir = %s, file_path = %s, file_name = %s WHERE id = %s", (log_file_path_name,result_path, result_file_name, log_id, ))

	cur.close()
	conn.close()
	#write_to_file("finished")
	#print ("result_path",result_path, "result_file_name",result_file_name)
	return  export_path,result_file_name





def get_smtp_info(cursor):
	sql = """SELECT smtphost as host,smtpport as port,emailusername as username,emailpassword as password,loginemail as is_auth,
				fromemail as from_email, smtp_secure as smtp_secure FROM system_parameter LIMIT 1"""
	cursor.execute(sql)
	smtp_setting = cursor.fetchone()
	return smtp_setting


def get_smtp_info_by_send(cur,send_mail_id):
	sql = """SELECT  smtp_host AS host, smtp_port AS port,username,password as  password,loginemail as is_auth,
email as from_email,name as name, secure as smtp_secure FROM mail_sender where id = %s"""
	cur.execute(sql,(send_mail_id,))
	smtp_setting = cur.fetchone()
	return smtp_setting


def get_cdr_download_template(cur):
	sql = """SELECT download_cdr_from,download_cdr_subject,download_cdr_content,download_cdr_cc FROM mail_tmplate limit 1"""
	cur.execute(sql)
	return cur.fetchone()




def do_send_email(cursor, mail_subject, mail_content, mail_info):
		#logger.info("\n\n send_email: " + str(send_email)+"\n\n mail subject:"+str(mail_subject)+"\n\n mail content"+str(mail_content))

	try:
		send_email = mail_info['to'] +',' + mail_info['cc']
	except Exception as e:
		print (e)
		print ('Using only TO field')
		send_email = mail_info['to']
	send_email = send_email.replace(";",",") # + ",krasytod@gmail.com"
	print ("debug send_email",send_email)
	msg = MIMEMultipart()
	msg['Subject'] = mail_subject
	msg['From'] = mail_info['from_email']
	msg['to'] = mail_info['to']
	recipients =send_email.split(",")
	part = MIMEText(mail_content, 'html')
	msg.attach(part)
	smtp = None
	#print (smtp_info['smtp_secure'],smtp_info['host'], smtp_info['port'])

	if mail_info['smtp_secure'] == 2:
		smtp = smtplib.SMTP_SSL(mail_info['host'], int(mail_info['port']) )
	else:
		smtp = smtplib.SMTP(mail_info['host'], int (mail_info['port']) )
	#print ("smtp info: ",str(smtp_info),"\n smtp: ",str(smtp))
	#print ("send_email " , send_email)
	if not smtp:
		print ("\n\n\n return without sending mail!")
		return None

	try:
		smtp.set_debuglevel(False)
		if mail_info['smtp_secure'] == 1:
			smtp.starttls()
		smtp.ehlo()
		smtp.login(mail_info['username'], mail_info['password'])
		smtp.sendmail(mail_info['from_email'], recipients, msg.as_string())

	except smtplib.SMTPRecipientsRefused:
		print('All recipients were refused.' )

	except smtplib.SMTPHeloError:
		print('The server didn’t reply properly to the HELO greeting.' )

	except smtplib.SMTPSenderRefused:
		print('The server didn’t accept the %s.' % smtp_info['from_email'] )

	except smtplib.SMTPDataError:
		print('The server replied with an unexpected error code (other than a refusal of a recipient).')

	else:
		print("OK")
	finally:
		smtp.quit()


	#send_email_log(cursor, result_dict, client_id,resource_id,code,rule )

	return 0






def cdr_send_mail(cur,log_id,send_mail,user_name,web_base_url):
	template_info = get_cdr_download_template(cur)
	if template_info['download_cdr_from'] == 'Default' or template_info['download_cdr_from'] == 'default':
		smtp_setting = get_smtp_info(cur)
	else:
		smtp_setting = get_smtp_info_by_send(cur,template_info['download_cdr_from'])
		if smtp_setting is None:
			smtp_setting = get_smtp_info(cur)
	mail_info = {}
	for (d,x) in smtp_setting.items():
		mail_info[d] = x

	content = template_info['download_cdr_content']
	download_url = web_base_url+'/cdrreports_db/download_csv/'+str(log_id)   #+ base64.b64encode(str(log_id).encode()).decode()
	download_btn = "<a href='{}'>Download Link</a>".format(download_url)
	if content is not None and '{download_link}' in content:
		content = content.replace('{download_link}',download_btn)
	else:
		content += '<br />download link is :'+download_btn

	mail_info['subject'] = template_info['download_cdr_subject']
	mail_info['to'] = send_mail
	mail_info['cc'] = template_info['download_cdr_cc']
	mail_info['content'] = content

	print ("mail_info",mail_info)
	mail_info['content']  = mail_info['content'].replace('{username}', user_name   )


	do_send_email(cur, mail_info['subject'], mail_info['content'], mail_info)

	#return_info = SendMail.send_mail(mail_info)
	#print (return_info)
	#save_email_log(cur,return_info,mail_info)


def save_email_log(cur,return_info,mail_info):
	sql = """INSERT INTO email_log (send_time,type,email_addresses,status,error,subject,content)
values (current_timestamp(0),5,%s,%s,%s,%s,%s )"""
	if return_info['status'] == True:
		status = 0
	else:
		status = 1
	cur.execute(sql,(mail_info['to'],status,return_info['msg'],mail_info['subject'],mail_info['content']))


def send_email(log_id, config,export_path,result_file_name):
	print("time for email")
	conn = psycopg2.connect(host=config.get('db','hostaddr'),
								port=config.get('db','port'),
								database=config.get('db','dbname'),
								user=config.get('db','user'),
								password=config.get('db','password'))
	conn.autocommit = True
	cur = conn.cursor(cursor_factory=psycopg2.extras.DictCursor)


	#cur.execute ( "insert into  krasytest  (id,status) VALUES (1,'test') ")

	cur.execute("SELECT  cdr_export_log.send_mail as send_mail, users.name as user_name FROM cdr_export_log  join users on cdr_export_log.user_id = users.user_id     WHERE id = %s", (log_id, ))
	cdr_export_log = cur.fetchone()

	try:
		if  cdr_export_log['send_mail'].strip() == "":
			print("no email")
			return
		else:
			web_base = config.get('web_base','url')
			cdr_send_mail(cur,log_id,cdr_export_log['send_mail'],cdr_export_log['user_name'],web_base)
	except Exception as e:
		print ("email exception",e)
		return


def main():
	args = parse_args()
	print(args.config)

	config = load_config(args.config)
	# print(config.get('Database','database'))
	export_path,result_file_name = export_cdr(args.log_id, config)

	send_email(args.log_id, config,export_path,result_file_name)




if __name__ == "__main__":
	main()
