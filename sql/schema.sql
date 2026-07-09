CREATE DATABASE IF NOT EXISTS TaskForce
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_0900_ai_ci;

USE TaskForce;

CREATE TABLE IF NOT EXISTS `city` (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    latitude     DECIMAL(10,7) NULL,
    longitude    DECIMAL(10,7) NULL,

    UNIQUE KEY uq_name (name)
);

CREATE TABLE IF NOT EXISTS `user` (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_role     VARCHAR(64) NOT NULL,
    hide_contacts BOOLEAN NOT NULL DEFAULT FALSE,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    email         VARCHAR(255) NOT NULL UNIQUE,
    name          VARCHAR(128) NOT NULL,
    password      VARCHAR(128) NULL,
    city_id       INT UNSIGNED NULL,
    avatar_path   VARCHAR(255) NULL,
    phone_number  VARCHAR(20)  NULL,
    birthday      DATE         NULL,
    telegram      VARCHAR(64)  NULL,

    CONSTRAINT chk_user_role CHECK (user_role IN ('customer', 'executor')),
    CONSTRAINT fk_user_city FOREIGN KEY (city_id) REFERENCES `city` (id)
);

CREATE TABLE IF NOT EXISTS `category` (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    slug VARCHAR(64) NOT NULL,

    UNIQUE KEY uq_category_name (name),
    UNIQUE KEY uq_category_slug (slug)
);

CREATE TABLE IF NOT EXISTS `user_category` (
    user_id     INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (user_id, category_id),

    CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES `user` (id),
    CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES `category` (id)
);

CREATE TABLE IF NOT EXISTS `task` (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    status_id    INT UNSIGNED  NOT NULL,
    category_id  INT UNSIGNED  NOT NULL,
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    title        VARCHAR(255)  NOT NULL,
    description  TEXT          NOT NULL,
    customer_id  INT UNSIGNED  NOT NULL,
    executor_id  INT UNSIGNED  NULL,
    city_id      INT UNSIGNED  NULL,
    latitude     DECIMAL(10,7) NULL,
    longitude    DECIMAL(10,7) NULL,
    budget       INT UNSIGNED  NULL,
    deadline     DATE          NULL,

    FULLTEXT KEY ft_title_description (title, description),

    INDEX idx_category_id (category_id),
    INDEX idx_customer_id (customer_id),
    INDEX idx_executor_id (executor_id),
    INDEX idx_city_id (city_id),

    CONSTRAINT fk_task_status FOREIGN KEY (status_id) REFERENCES `status` (id),
    CONSTRAINT fk_task_category FOREIGN KEY (category_id) REFERENCES `category` (id),
    CONSTRAINT fk_task_author FOREIGN KEY (author_id) REFERENCES `user` (id),
    CONSTRAINT fk_task_executor FOREIGN KEY (executor_id) REFERENCES `user` (id),
    CONSTRAINT fk_task_city FOREIGN KEY (city_id) REFERENCES `city` (id)
);

CREATE TABLE IF NOT EXISTS `file` (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    task_id    INT UNSIGNED NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    file_path  VARCHAR(64)  NULL,
    file_original_name VARCHAR(255) NULL,

    CONSTRAINT fk_file_task FOREIGN KEY (task_id) REFERENCES `task` (id)
);

CREATE TABLE IF NOT EXISTS `response` (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id    INT UNSIGNED NOT NULL,
    task_id    INT UNSIGNED NOT NULL,
    price      INT UNSIGNED NULL,
    comment    TEXT         NULL,
    status     VARCHAR(10)  NOT NULL DEFAULT 'new',

    UNIQUE KEY uq_user_task (user_id, task_id),

    INDEX idx_task_id (task_id),
    INDEX idx_user_id (user_id),

    CONSTRAINT chk_response_status CHECK (status IN ('new', 'accepted', 'rejected')),
    CONSTRAINT fk_response_user FOREIGN KEY (user_id) REFERENCES `user` (id),
    CONSTRAINT fk_response_task FOREIGN KEY (task_id) REFERENCES `task` (id)
);

CREATE TABLE IF NOT EXISTS `feedback` (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    customer_id INT UNSIGNED NOT NULL,
    executor_id INT UNSIGNED NOT NULL,
    task_id     INT UNSIGNED NOT NULL UNIQUE,
    evaluation  TINYINT UNSIGNED NOT NULL,
    comment     TEXT         NOT NULL,

    CHECK (evaluation BETWEEN 1 AND 5),

    INDEX idx_evaluation (evaluation),
    INDEX idx_customer_id (customer_id),
    INDEX idx_executor_id (executor_id),

    CONSTRAINT fk_feedback_customer FOREIGN KEY (customer_id) REFERENCES `user` (id),
    CONSTRAINT fk_feedback_xecutor FOREIGN KEY (executor_id) REFERENCES `user` (id),
    CONSTRAINT fk_feedback_task FOREIGN KEY (task_id) REFERENCES `task` (id)
);
