CREATE TABLE IF NOT EXISTS Competitions(
  -- Remember to refer to your proposal for your exact columns
  id int AUTO_INCREMENT PRIMARY KEY,
  comp_name varchar(240) not null,
  duration int default 3,
  expires TIMESTAMP DEFAULT (
    DATE_ADD(CURRENT_TIMESTAMP, INTERVAL duration DAY)
  ),
  current_reward int DEFAULT (starting_reward),
  starting_reward int DEFAULT 1,
  join_fee int default 1,
  current_participants int default 0,
  min_participants int DEFAULT 3,
  paid_out tinyint(1) DEFAULT 0,
  min_score int DEFAULT 1,
  first_place int default 70,
  second_place int default 20,
  third_place int default 10,
  cost_to_create int default (join_fee + starting_reward + 1),
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  creator_id int,
  FOREIGN KEY(creator_id) REFERENCES Users(id),
  check (min_score >= 1),
  check (starting_reward >= 1),
  check (current_reward >= starting_reward),
  check (min_participants >= 3),
  check (current_participants >= 0),
  check(join_fee >= 0)
)