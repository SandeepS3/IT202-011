CREATE TABLE CompetitionParticipants (
  id int AUTO_INCREMENT PRIMARY KEY,
  comp_id int,
  user_id int,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  unique key(user_id, comp_id),
  FOREIGN KEY (user_id) REFERENCES Users(id)
)