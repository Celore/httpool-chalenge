SET @book_id = 4;
select books.id as book_id, books.title, users.username, reviews.published_date, reviews.review_content
from reviews
         left join books on reviews.book_id = books.id
         left join users on reviews.user_id = users.id
where book_id = @book_id