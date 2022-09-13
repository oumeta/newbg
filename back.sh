#!/bin/bash
# description:  MySQL buckup shell script
# author:       Daniel
# web site:     http://home.ustc.edu.cn/~danewang/blog/

st=$(date +%s)
USER="root"
PASSWORD="56os_com"
 #用户名
DATABASE="levenbg"
#数据库用户密码
MAIL="861299678@qq.com"
#mail
BACKUP_DIR=/data/data_backup/ #备份文件存储路径
LOGFILE=/data/logs/data_backup.log #日志文件路径

DATE=`date +%Y%m%d-%H%M`
#用日期格式作为文件名
DUMPFILE=$DATE.sql
ARCHIVE=$DATE.sql.tar.gz
OPTIONS="-u$USER -p$PASSWORD $DATABASE"

#判断备份文件存储目录是否存在，否则创建该目录
if [ ! -d $BACKUP_DIR ]
then
	mkdir -p "$BACKUP_DIR"
fi

#开始备份之前，将备份信息头写入日记文件
echo "    ">> $LOGFILE
echo "--------------------" >> $LOGFILE
echo "BACKUP DATE:" $(date +"%y-%m-%d %H:%M:%S") >> $LOGFILE
echo "-------------------" >> $LOGFILE

#切换至备份目录
cd $BACKUP_DIR
#echo
 docker exec -it mysql5 /bin/bash -c 'exec mysqldump --databases levenbg -uroot -p56os_com'  > $DUMPFILE
#判断数据库备份是否成功
if [[ $? == 0 ]]
then
	tar czvf $ARCHIVE $DUMPFILE >> $LOGFILE 2>&1
	echo "[$ARCHIVE] Backup Successful!" >> $LOGFILE
	rm -f $DUMPFILE #删除原始备份文件,只需保留备份压缩包
	# 把压缩包文件备份到其他机器上。
	#scp -P 1110 $BACKUP_DIR$ARCHIVE ubuntu@*.*.*.*:/home/user/data_backup/ >> $LOGFILE &nbsp;2>&1
else
    echo "Database Backup Fail!" >> $LOGFILE
#备份失败后向管理者发送邮件提醒
#mail -s "database:$DATABASE Daily Backup Fail!" $MAIL
fi
echo "Backup Process Done"
#删除3天以上的备份文件
#Cleaning
find $BACKUP_DIR  -type f -mtime +2 -name "*.tar.gz" -exec rm -f {} \;