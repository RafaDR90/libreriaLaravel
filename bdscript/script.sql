use libreria_laravel;
show tables;

select * from users;
select * from libros;
select * from prestamos;
select * from anomalias;
delete from users;
delete from libros;
delete from prestamos;

select * from migrations;

INSERT INTO libros (titulo, descripcion, autor, lanzamiento, categoria, prestado, estado, eliminado, created_at, updated_at)
VALUES ('Libro 1', 'Descripción del Libro 1', 'Autor 1', '2022-01-01', 'Ficción', 'no', 'bueno', false, NOW(), NOW());

INSERT INTO libros (titulo, descripcion, autor, lanzamiento, categoria, prestado, estado, eliminado, created_at, updated_at)
VALUES ('Libro 2', 'Descripción del Libro 2', 'Autor 2', '2022-02-01', 'No Ficción', 'no', 'bueno', false, NOW(), NOW());

update users set rol = 'admin' where id = 2;
