drop table if exists tmp_solved;

create unlogged table tmp_solved(
  username varchar(30) primary key,
  solved bigint not null
);

-- --

drop table if exists tmp_tried;

create unlogged table tmp_tried(
  username varchar(30) primary key,
  tried bigint not null
);

-- --

drop table if exists tmp_submissions;

create unlogged table tmp_submissions(
  username varchar(30) primary key,
  submissions bigint not null
);

-- --

drop function if exists populate_user_stats();

CREATE FUNCTION populate_user_stats() RETURNS text AS $populate_user_stats$
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
  
  return 'ok';

END;
$populate_user_stats$ LANGUAGE plpgsql;

-- --

DROP TRIGGER IF EXISTS trig_record ON records;

DROP FUNCTION if exists user_stats_trigger();

CREATE FUNCTION user_stats_trigger() RETURNS TRIGGER AS $user_stats_trigger$
  BEGIN
    select populate_user_stats();
  END;
$user_stats_trigger$ LANGUAGE plpgsql;

CREATE TRIGGER trig_record
AFTER INSERT OR UPDATE OR DELETE ON records
  FOR EACH ROW EXECUTE PROCEDURE user_stats_trigger();
