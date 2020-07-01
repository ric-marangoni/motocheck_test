USE motorcheck;

CREATE TABLE owner (
    id INT NOT NULL PRIMARY KEY,
    name VARCHAR(100)
);

CREATE TABLE repository (
    id INT NOT NULL PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    watchers INT NOT NULL,
    forks INT NOT NULL,
    stars INT NOT NULL,
    url TEXT,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_owner_id FOREIGN KEY (owner_id) REFERENCES owner(id)
);