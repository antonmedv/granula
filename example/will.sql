INSERT INTO profile (id, city, birth, age, tags) VALUES (null, "Saint Petersburg", "2014-04-14 20:03:56", 21, "one,two")
INSERT INTO users (id, name, avatar, profile, friend) VALUES (null, "Anton", null, 10, 12)


• SHOW FULL TABLES WHERE Table_type = 'BASE TABLE' · []
• SELECT COLUMN_NAME AS Field, COLUMN_TYPE AS Type, IS_NULLABLE AS `Null`, COLUMN_KEY AS `Key`, COLUMN_DEFAULT AS `Default`, EXTRA AS Extra, COLUMN_COMMENT AS Comment, CHARACTER_SET_NAME AS CharacterSet, COLLATION_NAME AS CollactionName FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'granula' AND TABLE_NAME = 'profile' · []
• SELECT DISTINCT k.`CONSTRAINT_NAME`, k.`COLUMN_NAME`, k.`REFERENCED_TABLE_NAME`, k.`REFERENCED_COLUMN_NAME` /*!50116 , c.update_rule, c.delete_rule */ FROM information_schema.key_column_usage k /*!50116 INNER JOIN information_schema.referential_constraints c ON   c.constraint_name = k.constraint_name AND   c.table_name = 'profile' */ WHERE k.table_name = 'profile' AND k.table_schema = 'granula' /*!50116 AND c.constraint_schema = 'granula' */ AND k.`REFERENCED_COLUMN_NAME` is not NULL · []
• SELECT TABLE_NAME AS `Table`, NON_UNIQUE AS Non_Unique, INDEX_NAME AS Key_name, SEQ_IN_INDEX AS Seq_in_index, COLUMN_NAME AS Column_Name, COLLATION AS Collation, CARDINALITY AS Cardinality, SUB_PART AS Sub_Part, PACKED AS Packed, NULLABLE AS `Null`, INDEX_TYPE AS Index_Type, COMMENT AS Comment FROM information_schema.STATISTICS WHERE TABLE_NAME = 'profile' AND TABLE_SCHEMA = 'granula' · []
• SELECT COLUMN_NAME AS Field, COLUMN_TYPE AS Type, IS_NULLABLE AS `Null`, COLUMN_KEY AS `Key`, COLUMN_DEFAULT AS `Default`, EXTRA AS Extra, COLUMN_COMMENT AS Comment, CHARACTER_SET_NAME AS CharacterSet, COLLATION_NAME AS CollactionName FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'granula' AND TABLE_NAME = 'users' · []
• SELECT DISTINCT k.`CONSTRAINT_NAME`, k.`COLUMN_NAME`, k.`REFERENCED_TABLE_NAME`, k.`REFERENCED_COLUMN_NAME` /*!50116 , c.update_rule, c.delete_rule */ FROM information_schema.key_column_usage k /*!50116 INNER JOIN information_schema.referential_constraints c ON   c.constraint_name = k.constraint_name AND   c.table_name = 'users' */ WHERE k.table_name = 'users' AND k.table_schema = 'granula' /*!50116 AND c.constraint_schema = 'granula' */ AND k.`REFERENCED_COLUMN_NAME` is not NULL · []
• SELECT TABLE_NAME AS `Table`, NON_UNIQUE AS Non_Unique, INDEX_NAME AS Key_name, SEQ_IN_INDEX AS Seq_in_index, COLUMN_NAME AS Column_Name, COLLATION AS Collation, CARDINALITY AS Cardinality, SUB_PART AS Sub_Part, PACKED AS Packed, NULLABLE AS `Null`, INDEX_TYPE AS Index_Type, COMMENT AS Comment FROM information_schema.STATISTICS WHERE TABLE_NAME = 'users' AND TABLE_SCHEMA = 'granula' · []
• SHOW FULL TABLES WHERE Table_type = 'BASE TABLE' · []
• SELECT COLUMN_NAME AS Field, COLUMN_TYPE AS Type, IS_NULLABLE AS `Null`, COLUMN_KEY AS `Key`, COLUMN_DEFAULT AS `Default`, EXTRA AS Extra, COLUMN_COMMENT AS Comment, CHARACTER_SET_NAME AS CharacterSet, COLLATION_NAME AS CollactionName FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'granula' AND TABLE_NAME = 'profile' · []
• SELECT DISTINCT k.`CONSTRAINT_NAME`, k.`COLUMN_NAME`, k.`REFERENCED_TABLE_NAME`, k.`REFERENCED_COLUMN_NAME` /*!50116 , c.update_rule, c.delete_rule */ FROM information_schema.key_column_usage k /*!50116 INNER JOIN information_schema.referential_constraints c ON   c.constraint_name = k.constraint_name AND   c.table_name = 'profile' */ WHERE k.table_name = 'profile' AND k.table_schema = 'granula' /*!50116 AND c.constraint_schema = 'granula' */ AND k.`REFERENCED_COLUMN_NAME` is not NULL · []
• SELECT TABLE_NAME AS `Table`, NON_UNIQUE AS Non_Unique, INDEX_NAME AS Key_name, SEQ_IN_INDEX AS Seq_in_index, COLUMN_NAME AS Column_Name, COLLATION AS Collation, CARDINALITY AS Cardinality, SUB_PART AS Sub_Part, PACKED AS Packed, NULLABLE AS `Null`, INDEX_TYPE AS Index_Type, COMMENT AS Comment FROM information_schema.STATISTICS WHERE TABLE_NAME = 'profile' AND TABLE_SCHEMA = 'granula' · []
• SELECT COLUMN_NAME AS Field, COLUMN_TYPE AS Type, IS_NULLABLE AS `Null`, COLUMN_KEY AS `Key`, COLUMN_DEFAULT AS `Default`, EXTRA AS Extra, COLUMN_COMMENT AS Comment, CHARACTER_SET_NAME AS CharacterSet, COLLATION_NAME AS CollactionName FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'granula' AND TABLE_NAME = 'users' · []
• SELECT DISTINCT k.`CONSTRAINT_NAME`, k.`COLUMN_NAME`, k.`REFERENCED_TABLE_NAME`, k.`REFERENCED_COLUMN_NAME` /*!50116 , c.update_rule, c.delete_rule */ FROM information_schema.key_column_usage k /*!50116 INNER JOIN information_schema.referential_constraints c ON   c.constraint_name = k.constraint_name AND   c.table_name = 'users' */ WHERE k.table_name = 'users' AND k.table_schema = 'granula' /*!50116 AND c.constraint_schema = 'granula' */ AND k.`REFERENCED_COLUMN_NAME` is not NULL · []
• SELECT TABLE_NAME AS `Table`, NON_UNIQUE AS Non_Unique, INDEX_NAME AS Key_name, SEQ_IN_INDEX AS Seq_in_index, COLUMN_NAME AS Column_Name, COLLATION AS Collation, CARDINALITY AS Cardinality, SUB_PART AS Sub_Part, PACKED AS Packed, NULLABLE AS `Null`, INDEX_TYPE AS Index_Type, COMMENT AS Comment FROM information_schema.STATISTICS WHERE TABLE_NAME = 'users' AND TABLE_SCHEMA = 'granula' · []
• ALTER TABLE users CHANGE friend friend INT DEFAULT NULL, CHANGE profile profile INT DEFAULT NULL · []
• ALTER TABLE profile CHANGE user user INT DEFAULT NULL · []
• SELECT u.id AS u_id, u.name AS u_name, u.password AS u_password, u.email AS u_email, u.avatar AS u_avatar, u.profile AS u_profile, u.friend AS u_friend, u.date AS u_date, profile.id AS profile_id, profile.city AS profile_city, profile.date AS profile_date, profile.age AS profile_age, profile.tags AS profile_tags, profile.user AS profile_user, friend.id AS friend_id, friend.name AS friend_name, friend.password AS friend_password, friend.email AS friend_email, friend.avatar AS friend_avatar, friend.profile AS friend_profile, friend.friend AS friend_friend, friend.date AS friend_date FROM users u LEFT JOIN profile profile ON u.profile = profile.id LEFT JOIN users friend ON u.friend = friend.id WHERE u.id = ? LIMIT 1 · [1]
• UPDATE profile SET id = ?, city = ?, date = ?, age = ?, tags = ?, user = ? WHERE id = ? · [2,"345","2014-02-08 23:22:56",19,"one,two",1,2]


Entity\Profile Object
(
    [id:Entity\Profile:private] => 2
    [city:Entity\Profile:private] => 345
    [date:Entity\Profile:private] => DateTime Object
        (
            [date] => 2014-02-08 23:22:56
            [timezone_type] => 3
            [timezone] => Europe/Moscow
        )

    [age:Entity\Profile:private] => 19
    [tags:Entity\Profile:private] => Array
        (
            [0] => one
            [1] => two
        )

    [user:Entity\Profile:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\User
            [id:Granula\Lazy:private] => 1
        )

)
• SELECT * FROM users u WHERE u.id > ? · [1]
Entity\User Object
(
    [id:Entity\User:private] => 2
    [name:Entity\User:private] => Ola
    [password:Entity\User:private] =>
    [email:Entity\User:private] => ola@medvedev.be
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\Profile
            [id:Granula\Lazy:private] =>
        )

    [friend:Entity\User:private] =>
    [date:Entity\User:private] =>
)
Entity\User Object
(
    [id:Entity\User:private] => 4
    [name:Entity\User:private] => Ol'a
    [password:Entity\User:private] =>
    [email:Entity\User:private] => opazdalkina@medvedev.be
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\Profile
            [id:Granula\Lazy:private] =>
        )

    [friend:Entity\User:private] =>
    [date:Entity\User:private] =>
)
Entity\User Object
(
    [id:Entity\User:private] => 7
    [name:Entity\User:private] => Elfet
    [password:Entity\User:private] =>
    [email:Entity\User:private] => elfet@medvedev.be
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\Profile
            [id:Granula\Lazy:private] => 3
        )

    [friend:Entity\User:private] =>
    [date:Entity\User:private] =>
)
Entity\User Object
(
    [id:Entity\User:private] => 11
    [name:Entity\User:private] => Anton
    [password:Entity\User:private] =>
    [email:Entity\User:private] => 52fa2321c1001
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\Profile
            [id:Granula\Lazy:private] => 7
        )

    [friend:Entity\User:private] =>
    [date:Entity\User:private] =>
)
Entity\User Object
(
    [id:Entity\User:private] => 12
    [name:Entity\User:private] => Anton
    [password:Entity\User:private] =>
    [email:Entity\User:private] => 52fa236391d0e
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\Profile
            [id:Granula\Lazy:private] => 8
        )

    [friend:Entity\User:private] =>
    [date:Entity\User:private] =>
)
Entity\User Object
(
    [id:Entity\User:private] => 13
    [name:Entity\User:private] => Anton
    [password:Entity\User:private] =>
    [email:Entity\User:private] => 52fa23698923e
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\Profile
            [id:Granula\Lazy:private] => 9
        )

    [friend:Entity\User:private] =>
    [date:Entity\User:private] =>
)
• SELECT * FROM users u WHERE u.id IN (?) · [[1,2]]
• SELECT p.id AS p_id, p.city AS p_city, p.date AS p_date, p.age AS p_age, p.tags AS p_tags, p.user AS p_user, user.id AS user_id, user.name AS user_name, user.password AS user_password, user.email AS user_email, user.avatar AS user_avatar, user.profile AS user_profile, user.friend AS user_friend, user.date AS user_date FROM profile p LEFT JOIN users user ON p.user = user.id WHERE p.id = ? LIMIT 1 · [2]
Entity\User Object
(
    [id:Entity\User:private] => 1
    [name:Entity\User:private] => Anton
    [password:Entity\User:private] => new_password
    [email:Entity\User:private] => anton@elfet.ru
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Entity\Profile Object
        (
            [id:Entity\Profile:private] => 2
            [city:Entity\Profile:private] => 345
            [date:Entity\Profile:private] => DateTime Object
                (
                    [date] => 2014-02-08 23:22:56
                    [timezone_type] => 3
                    [timezone] => Europe/Moscow
                )

            [age:Entity\Profile:private] => 19
            [tags:Entity\Profile:private] => Array
                (
                    [0] => one
                    [1] => two
                )

            [user:Entity\Profile:private] => Entity\User Object
 *RECURSION*
        )

    [friend:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\User
            [id:Granula\Lazy:private] => 2
        )

    [date:Entity\User:private] => DateTime Object
        (
            [date] => 2014-02-07 13:32:29
            [timezone_type] => 3
            [timezone] => Europe/Moscow
        )

)
• INSERT INTO profile (id, city, date, age, tags, user) VALUES (?, ?, ?, ?, ?, ?) · [null,"Saint Petersburg","2014-04-14 20:03:56",21,"one,two",null]
• INSERT INTO users (id, name, password, email, avatar, profile, friend, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?) · [null,"Anton","1234","534c06ecb3163",null,"10",1,"2014-04-14 20:03:56"]
Entity\User Object
(
    [id:Entity\User:private] => 14
    [name:Entity\User:private] => Anton
    [password:Entity\User:private] => 1234
    [email:Entity\User:private] => 534c06ecb3163
    [avatar:Entity\User:private] =>
    [profile:Entity\User:private] => Entity\Profile Object
        (
            [id:Entity\Profile:private] => 10
            [city:Entity\Profile:private] => Saint Petersburg
            [date:Entity\Profile:private] => DateTime Object
                (
                    [date] => 2014-04-14 20:03:56
                    [timezone_type] => 3
                    [timezone] => Europe/Moscow
                )

            [age:Entity\Profile:private] => 21
            [tags:Entity\Profile:private] => Array
                (
                    [0] => one
                    [1] => two
                )

            [user:Entity\Profile:private] =>
        )

    [friend:Entity\User:private] => Granula\Lazy Object
        (
            [class:Granula\Lazy:private] => Entity\User
            [id:Granula\Lazy:private] => 1
        )

    [date:Entity\User:private] => DateTime Object
        (
            [date] => 2014-04-14 20:03:56
            [timezone_type] => 3
            [timezone] => Europe/Moscow
        )

)