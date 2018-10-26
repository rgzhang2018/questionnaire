/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2018/10/26 19:36:36                          */
/*==============================================================*/


drop table if exists messageboard;

drop table if exists question;

drop table if exists questionnaire;

drop table if exists selection;

drop table if exists user;

/*==============================================================*/
/* Table: messageboard                                          */
/*==============================================================*/
create table messageboard
(
   m_id                 int not null auto_increment,
   u_id                 int not null,
   m_message            varchar(300),
   m_name               varchar(45),
   m_time               int,
   primary key (m_id)
);

/*==============================================================*/
/* Table: question                                              */
/*==============================================================*/
create table question
(
   qq_id                int not null auto_increment,
   q_id                 int not null,
   qq_name              varchar(100),
   qq_type              int,
   primary key (qq_id)
);

/*==============================================================*/
/* Table: questionnaire                                         */
/*==============================================================*/
create table questionnaire
(
   q_id                 int not null auto_increment,
   u_id                 int not null,
   q_name               varchar(45),
   q_describe           varchar(200),
   q_starttime          int,
   q_endtime            int,
   primary key (q_id)
);

/*==============================================================*/
/* Table: selection                                             */
/*==============================================================*/
create table selection
(
   qs_id                int not null auto_increment,
   qq_id                int not null,
   qs_order             int,
   qs_name              varchar(180),
   qs_counts            int,
   primary key (qs_id)
);

/*==============================================================*/
/* Table: user                                                  */
/*==============================================================*/
create table user
(
   u_id                 int not null auto_increment,
   u_email              varchar(45),
   u_password           varchar(45),
   u_name               varchar(45),
   primary key (u_id)
);

alter table messageboard add constraint FK_user_messagebord foreign key (u_id)
      references user (u_id) on delete restrict on update restrict;

alter table question add constraint FK_q_question foreign key (q_id)
      references questionnaire (q_id) on delete restrict on update restrict;

alter table questionnaire add constraint FK_user_q foreign key (u_id)
      references user (u_id) on delete restrict on update restrict;

alter table selection add constraint FK_question_selection foreign key (qq_id)
      references question (qq_id) on delete restrict on update restrict;

