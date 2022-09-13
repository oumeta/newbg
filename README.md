
进入docker 后更新权限

  chown -R www-data:www-data *
  chmod -R 755 Admin/


docker exec some-mysql sh -c 'exec mysqldump --databases db1 -uroot -p"$MYSQL_ROOT_PASSWORD"' > /some/path/on/your/host/db1.sql
docker exec -it mysql5 /bin/bash -c 'exec mysqldump --databases levenbg -uroot -p56os_com' >/data/data_backup/db1.sql
————————————————
原文作者：mjczz
转自链接：https://learnku.com/articles/44349
版权声明：著作权归作者所有。商业转载请联系作者获得授权，非商业转载请保留以上作者信息和原文链接。