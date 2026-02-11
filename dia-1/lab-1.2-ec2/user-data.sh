#!/bin/bash
dnf update -y
dnf install -y httpd
systemctl start httpd
systemctl enable httpd
HOSTNAME=$(hostname)
echo "<html><body><h1>Bienvenido al Workshop AWS</h1><p>Hostname: $HOSTNAME</p></body></html>" > /var/www/html/index.html
