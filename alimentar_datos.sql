/*ALIMENTAR ACT_CATEGORIAS*/

INSERT INTO `act_categorias` (`idCategoria`, `nombreCategoria`) VALUES ('A', 'Categoria para 1º y 2º ESO'), ('B', 'Categoria para 3º y 4º ESO');

/*ALIMENTAR ACT_MOMENTO*/

INSERT INTO `act_momento` (`idMomento`, `nombreMomento`) VALUES (NULL, 'Torneo Guadalupe'), (NULL, 'Concursos Navidad');

/*ALIMENTAR ACT_CURSOS*/

INSERT INTO `act_cursos` (`codCurso`, `nombreCurso`, `idCategoria`) VALUES (NULL, '1º E.S.O', 'A'), (NULL, '2º E.S.O', 'A');
INSERT INTO `act_cursos` (`codCurso`, `nombreCurso`, `idCategoria`) VALUES (NULL, '3º E.S.O', 'B'), (NULL, '4º E.S.O', 'B');