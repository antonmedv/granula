# Granula PHP Object Relational Mapper

![Problem](/docs/problem.png)

Common Object Relational Mapper generates query to database and mapping result to objects.
![ORM](/docs/orm.png)

Every row in database table represented by a single object in application.
![Schema](/docs/table_to_object.png)

Granula ORM using DBAL (Database Abstraction & Access Layer) and provide automatic SQL generation, automatic mapping and manual SQL writing with automatic/manual mapping.

In Granula ORM represented:
- [x] Auto schema generation
- [x] Auto schema upgrade
- [x] Active Record implementation
- [ ] Data Mapper implementation
- [x] Field mapping
- [x] Field types
- [x] Application <--> Database transformation
- [x] One-to-One association
- [x] Lazy and eager loading for one-to-one association
- [ ] One-to-Many association
- [ ] Many-to-May association
