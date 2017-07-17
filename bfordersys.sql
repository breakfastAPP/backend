create database sellsystem;
use sellsystem;
create table user(
	userID int auto_increment,
	username varchar(32),
	userpwd varchar(32),
	useraccount char(20),
	payPwd char(6),
	useraddress varchar(255),
	phone char(11),
	primary key(userID),
	unique(username)
);
create table seller(
	sellID int auto_increment,
	sellname varchar(32),
	sellaccount char(20),
	seraddress varchar(255),
	primary key(sellID),
	unique(sellname),
	unique(sellaccount)
);
create table breakfast(
	foodID int auto_increment,
	foodname varchar(32),
	price double,
	total int,
	type varchar(32),
	isrcd int,
	primary key(foodID),
	unique(foodname)
);
create table orders(
	orderID int auto_increment,
	username varchar(32),
	sellname varchar(32),
	createdTime DATETIME,
	arrivingTime varchar(32),
	sumMoney double,
	content text,
	state int,
	primary key(orderID),
	foreign key(username) REFERENCES user(username),
	foreign key(sellname) REFERENCES seller(sellname)
);
create table preorders(
	username varchar(32),
	sellname varchar(32),
	sumMoney double,
	content text,
	arrivingTime varchar(32),
	foreign key(username) REFERENCES user(user),
	foreign key(sellname) REFERENCES seller(seller)
);
create table bank(
	name varchar(32),
	account char(20),
	sumMoney double,
	payPwd char(6),
	primary key(account)
);
create table recommend(
	articleID int auto_increment,
	title varchar(32),
	content text,
	createdTime DATETIME,
	primary key(articleID)
);
alter table XXX convert to character set utf8;

