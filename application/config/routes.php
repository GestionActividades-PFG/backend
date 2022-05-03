<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'C_GestionEVG';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* ---------------------------------------------------
    INSTALACIÓN
----------------------------------------------------- */
$route['installation'] = 'C_Instalacion';
$route['add-admin'] = 'C_Instalacion/formularioAdmin';

/* ---------------------------------------------------
    GRID
----------------------------------------------------- */
$route['main'] = 'Grid';
$route['app/(:num)'] = 'Grid/vistaGeneral/$1';

/* ---------------------------------------------------
    APPS
----------------------------------------------------- */
$route['apps'] = 'C_AdministracionEVG/VerApps';
$route['add-app'] = 'C_AdministracionEVG/anadirAppForm';
$route['update-app/(:num)'] = 'C_AdministracionEVG/modificarAppForm/$1';
$route['profiles-app/(:num)'] = 'C_AdministracionEVG/perfilesAplicacion/$1';
$route['delete-app/(:num)'] = 'C_AdministracionEVG/borrarApp/$1';

/* ---------------------------------------------------
    PROFILES
----------------------------------------------------- */
$route['profiles'] = 'C_AdministracionEVG/VerPerfiles';
$route['add-profile'] = 'C_AdministracionEVG/anadirPerfilForm';
$route['update-profile/(:num)'] = 'C_AdministracionEVG/modificarPerfilForm/$1';
$route['users-profile/(:num)'] = 'C_AdministracionEVG/usuariosPerfil/$1';
$route['delete-profile/(:num)'] = 'C_AdministracionEVG/borrarPerfil/$1';

/* ---------------------------------------------------
    STAGES
----------------------------------------------------- */
$route['stages'] = 'C_GestionEVG/VerEtapas';
$route['add-stage'] = 'C_GestionEVG/anadirEtapaForm';
$route['update-stage/(:num)'] = 'C_GestionEVG/modificarEtapaForm/$1';
$route['father-stage/(:num)'] = 'C_GestionEVG/etapaPadre/$1';
$route['delete-stage/(:num)'] = 'C_GestionEVG/borrarEtapa/$1';

/* ---------------------------------------------------
    COURSES
----------------------------------------------------- */
$route['courses'] = 'C_GestionEVG/VerCursos';
$route['add-course'] = 'C_GestionEVG/anadirCursoForm';
$route['update-course/(:num)'] = 'C_GestionEVG/modificarCursoForm/$1';
$route['stage-course/(:num)'] = 'C_GestionEVG/asignarEtapaCursoForm/$1';
$route['delete-course/(:num)'] = 'C_GestionEVG/borrarCurso/$1';
$route['import-courses'] = 'C_GestionEVG/importarCursosForm';

/* ---------------------------------------------------
    CYCLES
----------------------------------------------------- */
$route['cycles'] = 'C_GestionEVG/VerCiclos';
$route['add-cycle'] = 'C_GestionEVG/anadirCicloForm';
$route['update-cycle/(:num)'] = 'C_GestionEVG/modificarCicloForm/$1';
$route['courses-cycle/(:num)'] = 'C_GestionEVG/cursosCiclo/$1';
$route['delete-cycle/(:num)'] = 'C_GestionEVG/borrarCiclo/$1';

/* ---------------------------------------------------
    SECTIONS
----------------------------------------------------- */
$route['sections'] = 'C_GestionEVG/VerSecciones';
$route['add-section'] = 'C_GestionEVG/anadirSeccionForm';
$route['update-section/(:num)'] = 'C_GestionEVG/modificarSeccionForm/$1';
$route['tutor-section/(:num)'] = 'C_GestionEVG/asignarTutorForm/$1';
$route['delete-section/(:num)'] = 'C_GestionEVG/borrarSeccion/$1';
$route['import-sections'] = 'C_GestionEVG/importarSeccionesForm';

/* ---------------------------------------------------
    STUDENTS
----------------------------------------------------- */
$route['students'] = 'C_GestionEVG/VerAlumnos';
$route['add-student'] = 'C_GestionEVG/anadirAlumnoForm';
$route['update-student/(:num)/(:num)'] = 'C_GestionEVG/modificarAlumnoForm/$1/$2';
$route['sections-stage/(:num)'] = 'C_GestionEVG/verSeccionesEtapa/$1';
$route['section-students/(:num)/(:num)'] = 'C_GestionEVG/verAlumnosSeccion/$1/$2';
$route['delete-student/(:num)/(:num)/(:num)'] = 'C_GestionEVG/borrarAlumno/$1/$2/$3';
$route['import-students'] = 'C_GestionEVG/importarAlumnosForm';

/* ---------------------------------------------------
    USERS
----------------------------------------------------- */
$route['users'] = 'C_GestionEVG/VerUsuarios';
$route['add-user'] = 'C_GestionEVG/anadirUsuarioForm';
$route['update-user/(:num)'] = 'C_GestionEVG/modificarUsuarioForm/$1';
$route['delete-user/(:num)'] = 'C_GestionEVG/borrarUsuario/$1';
$route['import-users'] = 'C_GestionEVG/importarUsuariosForm';

/* ---------------------------------------------------
    DEPARTMENTS
----------------------------------------------------- */
$route['departments'] = 'C_GestionEVG/VerDepartamentos';
$route['add-department'] = 'C_GestionEVG/anadirDepartamentoForm';
$route['update-department/(:num)'] = 'C_GestionEVG/modificarDepartamentoForm/$1';
$route['delete-department/(:num)'] = 'C_GestionEVG/borrarDepartamento/$1';

/* ---------------------------------------------------
    FAMILIES
----------------------------------------------------- */
$route['families'] = 'C_GestionEVG/VerFamilias';
$route['add-family'] = 'C_GestionEVG/anadirFamiliaForm';
$route['update-family/(:num)'] = 'C_GestionEVG/modificarFamiliaForm/$1';
$route['delete-family/(:num)'] = 'C_GestionEVG/borrarFamilia/$1';

/* ---------------------------------------------------
    TUTOR LIST
----------------------------------------------------- */
$route['tutor-list'] = 'C_GestionEVG/listadoTutores';
