USE `library`;

DROP TABLE IF EXISTS `library`.`users`;
DROP TABLE IF EXISTS `library`.`books`;
DROP TABLE IF EXISTS `library`.`addresses`;
DROP TABLE IF EXISTS `library`.`user_books`;
DROP TABLE IF EXISTS `library`.`reviews`;

CREATE TABLE `library`.`users` ( `id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(25) NOT NULL , `enabled` BOOLEAN NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `library`.`books` ( `id` INT NOT NULL AUTO_INCREMENT, `title` VARCHAR(100) NOT NULL , `author` VARCHAR(100) NOT NULL , `published_date` TIMESTAMP NOT NULL , `isbn` INT NOT NULL, PRIMARY KEY (`id`) ) ENGINE = InnoDB;
CREATE TABLE `library`.`addresses` ( `user_id` INT NOT NULL , `street` VARCHAR(30) NOT NULL , `city` VARCHAR(30) NOT NULL , `state` VARCHAR(30) NOT NULL , PRIMARY KEY (`user_id`)) ENGINE = InnoDB;
CREATE TABLE `library`.`user_books` ( `user_id` INT NOT NULL , `book_id` INT NOT NULL , `checkout_date` TIMESTAMP NOT NULL , `return_date` TIMESTAMP NOT NULL ) ENGINE = InnoDB;
CREATE TABLE `library`.`reviews` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `book_id` INT NOT NULL , `review_content` VARCHAR(255) NOT NULL , `published_date` TIMESTAMP NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

INSERT INTO `users` (`id`, `username`, `enabled`) VALUES (NULL, 'John Doe', '1'), (NULL, 'Jane Doe', '1'), (NULL, 'Peter Novak', '1'), (NULL, 'Luka Doncic', '1'), (NULL, 'LeBron James', '1'), (NULL, 'Goran Dragic', '1');
INSERT INTO `books` (`id`, `title`, `author`, `published_date`, `isbn`) VALUES (NULL, 'Harry Potter', 'JK Rowling', '1998-04-01 16:11:16', '100'), (NULL, 'Lord Of The Rings', 'J. R. R. Tolkien', '2014-04-24 16:11:16', '166'), (NULL, 'Mamba Mentality', 'Kobe Bryant', '2020-09-09 16:13:07', '1234'), (NULL, 'The Song of Ice and Fire', 'George R. R. Martin', '2015-04-10 16:13:07', '1231'), (NULL, 'A Tale of Two Cities', 'Charles Dickens', '2015-04-17 16:13:07', '1444');
INSERT INTO `addresses` (`user_id`, `street`, `city`, `state`) VALUES ('2', 'Doe Road', 'New York', 'New York'), ('3', 'Beverly Hills 10', 'Los Angeles', 'California');
INSERT INTO `reviews` (`id`, `user_id`, `book_id`, `review_content`, `published_date`) VALUES (NULL, '2', '4', 'Cool, but a hard read', current_timestamp()), (NULL, '2', '3', 'Got bored and returned it', current_timestamp()), (NULL, '4', '4', 'GOAT Book, a must read!', current_timestamp()), (NULL, '3', '1', 'I read it once as a kid but I can\'t really remember it', current_timestamp()), (NULL, '3', '2', 'I didn\'t read this book', current_timestamp());
INSERT INTO `user_books` (`user_id`, `book_id`, `checkout_date`, `return_date`) VALUES ('1', '1', '2022-03-10 16:20:01', NULL), ('1', '3', '2022-04-01 16:20:01', '2022-04-04 16:20:01'), ('3', '3', '2022-04-04 16:20:46', NULL), ('3', '5', '2022-03-02 16:20:46', '2022-04-04 16:20:46'), ('4', '1', '2022-02-01 16:20:46', '2022-04-02 16:20:46'), ('4', '4', '2022-02-01 16:20:46', NULL);
