# Here I joined tables addresses and user_books directly on user_id column.
# I'm not sure if I am able to do it, but there is no rules in the task,
# that I should follow relationship diagram
select addresses.city, count(user_books.user_id) as number_of_books
from addresses
         left join user_books on addresses.user_id = user_books.user_id
     # We can use conditional below to get only non returned books in current moment
     # and user_books.return_date > now()
group by addresses.city;


# Here I joined tables addresses and user_books through the users table to follow relationship diagram
select addresses.city, count(ub.user_id) as number_of_books
from addresses left join users u on addresses.user_id = u.id left join user_books ub on u.id = ub.user_id
group by addresses.city;