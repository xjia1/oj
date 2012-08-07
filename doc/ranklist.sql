drop procedure if exists populate_user_stats;

delimiter //

CREATE PROCEDURE populate_user_stats()
BEGIN

  create temporary table tmp_solved(
    username varchar(30) primary key,
    solved bigint not null
  );
  create temporary table tmp_tried(
    username varchar(30) primary key,
    tried bigint not null
  );
  create temporary table tmp_submissions(
    username varchar(30) primary key,
    submissions bigint not null
  );

  create temporary table tmp_solved_join(
    username varchar(30) primary key,
    solved bigint not null
  );
  create temporary table tmp_tried_join(
    username varchar(30) primary key,
    solved bigint not null,
    tried bigint not null
  );

  insert into tmp_solved(username,solved)
    select owner,count(distinct problem_id) from records where verdict=1 group by owner;

  insert into tmp_tried(username,tried)
    select owner,count(distinct problem_id) from records group by owner;

  insert into tmp_submissions(username,submissions)
    select owner,count(1) from records group by owner;

  insert into tmp_solved_join(username,solved)
    select u.username, v.solved
    from users as u left join tmp_solved as v on u.username=v.username;

  insert into tmp_tried_join(username,solved,tried)
    select v.username, v.solved, t.tried
    from tmp_solved_join as v left join tmp_tried as t on v.username=t.username;

  truncate table user_stats;

  insert into user_stats(username,solved,tried,submissions)
    select t.username, t.solved, t.tried, s.submissions
    from tmp_tried_join as t left join tmp_submissions as s on t.username=s.username;

  drop table tmp_solved_join;
  drop table tmp_tried_join;

  drop table tmp_solved;
  drop table tmp_tried;
  drop table tmp_submissions;

END
//

delimiter ;
