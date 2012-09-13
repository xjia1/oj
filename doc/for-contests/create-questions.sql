create table questions (
  id serial,
  general smallint not null, -- true/false
  problem_id bigint,
  ask_time timestamp not null,
  question text not null,
  answer_time timestamp,
  answer text,
  primary key (id)
);