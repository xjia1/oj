drop table if exists tmp_solved;

create table tmp_solved(
  username varchar(30) primary key,
  solved bigint not null
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --

drop table if exists tmp_tried;

create table tmp_tried(
  username varchar(30) primary key,
  tried bigint not null
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --

drop table if exists tmp_submissions;

create table tmp_submissions(
  username varchar(30) primary key,
  submissions bigint not null
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --

drop procedure if exists populate_user_stats;

delimiter //

CREATE PROCEDURE populate_user_stats()
BEGIN

  delete from tmp_solved;
  delete from tmp_tried;
  delete from tmp_submissions;
  delete from user_stats;

  insert into tmp_solved(username,solved)
    select owner,count(distinct problem_id) from records where verdict=1 group by owner;
  
  insert into tmp_tried(username,tried)
    select owner,count(distinct problem_id) from records group by owner;
  
  insert into tmp_submissions(username,submissions)
    select owner,count(1) from records group by owner;

  insert into tmp_solved(username,solved)
    select u.username, 0
    from users as u left join tmp_solved as v on u.username=v.username
    where v.username is null;

  insert into tmp_tried(username,tried)
    select u.username, 0
    from users as u left join tmp_tried as t on u.username=t.username
    where t.username is null;

  insert into tmp_submissions(username,submissions)
    select u.username, 0
    from users as u left join tmp_submissions as s on u.username=s.username
    where s.username is null;

  -- insert into user_stats(username,solved,tried,submissions)
  --   select u.username, v.solved, t.tried, s.submissions
  --   from users as u, tmp_solved as v, tmp_tried as t, tmp_submissions as s
  --   where u.username=v.username and u.username=t.username and u.username=s.username;
  insert into user_stats(username,solved,tried,submissions)
    select u.username, v.solved, t.tried, s.submissions
    from users as u natural join tmp_solved as v natural join tmp_tried as t natural join tmp_submissions as s;

END
//

delimiter ;

-- --

drop trigger if exists trig_insert_record;

delimiter //

CREATE TRIGGER trig_insert_record after insert ON records
FOR EACH ROW BEGIN
  call populate_user_stats();
END
//

delimiter ;

-- --

drop trigger if exists trig_update_record;

delimiter //

CREATE TRIGGER trig_update_record after update ON records
FOR EACH ROW BEGIN
  call populate_user_stats();
END
//

delimiter ;
