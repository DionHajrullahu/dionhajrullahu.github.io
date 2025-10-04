--SQL Structure IDEAS:
-- Data structure for property:

create database empire_living;
use empire_living;

create table user (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(20) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  role varchar(10) NOT NULL DEFAULT 'user' -- basically used to identify who's  staff or not
);

CREATE TABLE property (
  property_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- who posted the property
  title VARCHAR(30) NOT NULL,
  cost INT NOT NULL,
  square_feet INT NOT NULL,
  bedrooms INT NOT NULL,
  bathrooms INT NOT NULL,
  image LONGBLOB,
  location VARCHAR(50) NOT NULL, -- like the name of the neighborhood
  address VARCHAR(255) NOT NULL, -- full address for details page
  sold BOOLEAN DEFAULT FALSE,
  description TEXT,
  FOREIGN KEY (user_id) REFERENCES user(user_id)
);

CREATE TABLE agent (
  agent_id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL
);

create table appointment (
  appointment_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  property_id INT NOT NULL,
  agent_id INT NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(user_id),
  FOREIGN KEY (agent_id) REFERENCES agent(agent_id),
  FOREIGN KEY (property_id) REFERENCES property (property_id)
);

-- Creating Agents
INSERT INTO agent (email, first_name, last_name) VALUES ('john.smith@empire-living.com', 'John', 'Smith');
INSERT INTO agent (email, first_name, last_name) VALUES ('frederick.jefferson@empire-living.com', 'Frederick', 'Jefferson');
INSERT INTO agent (email, first_name, last_name) VALUES ('annabelle.smalls@empire-living.com', 'Annabelle', 'Smalls');

-- Creating staff roles
insert into user (username, email, password, role) VALUES ('admin', 'admin@admin.com', 'admin', 'staff');

-- Creating some users
insert into user (username, email, password) VALUES ('test', 'test@test.com', 'test');
insert into user (username, email, password) VALUES ('agon_shehu', 'agon_shehu@empire-living.com', 'agon_shehu');
insert into user (username, email, password) VALUES ('agon_surdulli', 'agon_surdulli@empire-living.com', 'agon_surdulli');
insert into user (username, email, password) VALUES ('dion_hajrullahu', 'dion_hajrullahu@empire-living.com', 'dion_hajrullahu');
insert into user (username, email, password) VALUES ('erin_kupina', 'erin_kupina@empire-living.com', 'erin_kupina');

-- Creating default properties available for purchase for testing purposes.
insert into property (user_id, title, cost, square_feet, bedrooms, bathrooms, location) VALUES (1, 'Luxury Penthouse', 5500000, 2800, 3, 3, 'Upper East Side');
insert into property (user_id, title, cost, square_feet, bedrooms, bathrooms, location) VALUES (1, 'Modern Loft', 3200000, 1800, 2, 2, 'SoHo');

-- SQL Database Made by Agon Shehu, Erin Kupina and Dion Hajrullahu I declare no AI or other similar services was used to create this database.