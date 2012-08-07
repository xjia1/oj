select owner,problem_id,verdict from records 

Solved:
select owner, count(distinct problem_id) as solved from records where verdict=1 group by owner order by solved desc

Tried:
select owner, count(distinct problem_id) as tried from records group by owner order by tried desc

Submissions:
select owner, count(1) as submissions from records group by owner order by submissions desc

----

create temporary table tmp_solved (
  username varchar(30) not null primary key,
  solved bigint not null
);
create temporary table tmp_tried (
  username varchar(30) not null primary key,
  tried bigint not null
);
create temporary table tmp_submissions (
  username varchar(30) not null primary key,
  submissions bigint not null
);

insert into tmp_solved(username,solved)
select owner,count(distinct problem_id) from records where verdict=1 group by owner;
insert into tmp_tried(username,tried)
select owner,count(distinct problem_id) from records group by owner;

insert into tmp_submissions(username,submissions)
select owner,count(1) from records group by owner;

delete from user_stats;

insert into user_stats(username,solved,tried,submissions)
select u.username, v.solved, t.tried, s.submissions
from users as u, tmp_solved as v, tmp_tried as t, tmp_submissions as s
where u.username=v.username and u.username=t.username and u.username=s.username;

drop table tmp_solved;
drop table tmp_tried;
drop table tmp_submissions;
