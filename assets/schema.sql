CREATE TABLE IF NOT EXISTS users (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name  VARCHAR(128)    NOT NULL,
  last_name   VARCHAR(128)    NOT NULL,
  email       VARCHAR(128)    NOT NULL UNIQUE,
  password    VARCHAR(256)    NOT NULL,
  external_id VARCHAR(320),
  status      INT UNSIGNED DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS countries (
  id   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(128)    NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS states (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(56)     NOT NULL,
  country_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (country_id) REFERENCES countries (id)
    ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS cities (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(128)    NOT NULL,
  country_id BIGINT UNSIGNED NOT NULL,
  state_id   BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (country_id) REFERENCES countries (id)
    ON DELETE CASCADE,
  FOREIGN KEY (state_id) REFERENCES states (id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS institutes (
  id      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name    VARCHAR(256)    NOT NULL,
  city_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (city_id) REFERENCES cities (id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS courses (
  id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name         VARCHAR(256)    NOT NULL,
  institute_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (institute_id) REFERENCES institutes (id)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS departments (
  id   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(128)    NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS notes (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  department_id BIGINT UNSIGNED,
  course_id     BIGINT UNSIGNED NOT NULL,
  institute_id  BIGINT UNSIGNED,
  user_id       BIGINT UNSIGNED,
  link          VARCHAR(256),
  PRIMARY KEY (id),
  FOREIGN KEY (department_id) REFERENCES departments (id)
    ON DELETE SET NULL,
  FOREIGN KEY (course_id) REFERENCES courses (id)
    ON DELETE CASCADE,
  FOREIGN KEY (institute_id) REFERENCES institutes (id)
    ON DELETE SET NULL,
  FOREIGN KEY (user_id) REFERENCES users (id)
    ON DELETE SET NULL
);