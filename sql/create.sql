CREATE TABLE users (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL
);

CREATE TABLE module (
    id INT AUTO_INCREMENT  PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    expiration_date DATE
);

CREATE TABLE noticeboard (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    number INT NOT NULL,
    date DATE NOT NULL
);


CREATE TABLE attachment (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    file VARCHAR(255) NOT NULL,
    number INT NOT NULL
);

CREATE TABLE groups (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    target VARCHAR(255) NOT NULL,
    members INT,
    expiration_date DATE 
);

CREATE TABLE have (
    notice_id INT,
    attach_id INT,
    PRIMARY KEY (notice_id, attach_id),
    FOREIGN KEY (notice_id) REFERENCES noticeboard(id),
    FOREIGN KEY (attach_id) REFERENCES attachment(id)
);

CREATE TABLE refers (
    notice_id INT,
    group_id INT,
    PRIMARY KEY (notice_id, group_id),
    FOREIGN KEY (notice_id) REFERENCES noticeboard(id),
    FOREIGN KEY (group_id) REFERENCES groups(id)
);

CREATE TABLE has_viewed (
    notice_id INT,
    user_id VARCHAR(255),
    PRIMARY KEY (notice_id, user_id),
    FOREIGN KEY (notice_id) REFERENCES noticeboard(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE is_part_of (
    notice_id INT,
    user_id VARCHAR(255),
    PRIMARY KEY (notice_id, user_id),
    FOREIGN KEY (notice_id) REFERENCES noticeboard(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE compiles (
    user_id VARCHAR(255),
    module_id INT,
    file VARCHAR(255),
    PRIMARY KEY (user_id, module_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (module_id) REFERENCES module(id)
);

CREATE TABLE likes (
    notice_id INT,
    user_id VARCHAR(255),
    PRIMARY KEY (notice_id, user_id),
    FOREIGN KEY (notice_id) REFERENCES noticeboard(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);