# REST API

* Url basico http://icsseseguridad.com/api/public/
* la api acepta consultas GET, POST, PUT y DELETE  segun sea el caso
* todas las respuesta son en formato JSON
* todas las consulta con POST y PUT el body va con formato JSON
* Usar Postman, Restlet Client, o alguna otra extension de chrome para probar las url
* Probar los GET, POST, PUT, DELETE para entender los resultados y trabajar con ellos
* En las url, lo que este entre {} es por lo que debe remplazar. Ejemplo:

"http://icsseseguridad.com/api/public/vehicle/{imei}"

se rempleaza por 

"http://icsseseguridad.com/api/public/vehicle/867162025555236"

los GET responden con la lista de objectos solicitados y el total

    http://icsseseguridad.com/api/public/incidence
    
    {
        "data": [
            {
                "id": "5",
                "name": "Otro"
            },
            {
                "id": "4",
                "name": "Ingreso no Autorizado"
            },
            {
                "id": "2",
                "name": "Robo"
            }
        ],
        "total": "4"
    }

Si es un GET especifico, osea con un id, cedula, etc, la respuesta es el objecto solamente

    http://icsseseguridad.com/api/public/incidence/5
    
    {
        "id": "5",
        "name": "Otro"
    }

Si la solicitud get al objecto especifico no arroja ninguna resultado la respuesta sera un simple false
    
    http://icsseseguridad.com/api/public/incidence/58
    
    false
    
    
los POST, PUT y DELETE tienen la respuesta con el siguiente formato

    {
        "result": null,
        "response": true,
        "message": "",
        "errors": []
    }
    
Algunos POST y PUT devolveran datos en el 'result' segun sea necesario, por ejemplo el LOGIN que devuelve el usuario con el token de session

    {
        "result": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1MzQyODcwMDcsImF1ZCI6IjBkN2NiNjBiYmY1ZTQxYmYxMGFjM2FjMzZjOWM4NzAwZDQ2MjlhYmYiLCJkYXRhIjp7ImlkIjoiMSIsImRuaSI6IjIwMzU2ODQxIiwibmFtZSI6Illvcm5lbCIsImxhc3RuYW1lIjoiTWFydmFsIiwiaXNBZG1pbiI6dHJ1ZX19.t5jZREVK7-v_SSYm23cN_e7Y4ylcK3KHybPFJLfD0ZA",
        "response": true,
        "message": "",
        "errors": []
    }
    
Si response es 'true' quiere decir que el registro, actualizacion o borrado fue exitoso, si es 'false' quiere decir que hubo algun error
y habra un mensaje en 'message' y en 'errors' una lista de lo errores.

    {
        "result": null,
        "response": false,
        "message": "Ocurrio un error inesperado.",
        "errors": {
            "name": [
                "debe contener como minimo 4 caracteres"
            ],
            "lastname": [
                "debe contener como minimo 4 caracteres"
            ]
        }
    }
    

# Las URL existentes


Recomendacion: Se deberia agregar una variable estatica de sistema con el siguiente valor = http://icsseseguridad.com/api/public/ y agregarle el path
asi en caso de cambiar la url del servidor solo de modificara la variable estatica.

# Logeo (auth)
POST http://icsseseguridad.com/api/public/auth/admin

    logeo de administradores, recibe por body
    * dni
    * password
    
    
POST http://icsseseguridad.com/api/public/auth/guard

    logeo de guardias, recibe por body
    * dni
    * password
    
tanto en logeo de admin como de guardia la respuesta de ser exitosa sera como la siguiente: 

    {
        "result": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1MzQyODcwMDcsImF1ZCI6IjBkN2NiNjBiYmY1ZTQxYmYxMGFjM2FjMzZjOWM4NzAwZDQ2MjlhYmYiLCJkYXRhIjp7ImlkIjoiMSIsImRuaSI6IjIwMzU2ODQxIiwibmFtZSI6Illvcm5lbCIsImxhc3RuYW1lIjoiTWFydmFsIiwiaXNBZG1pbiI6dHJ1ZX19.t5jZREVK7-v_SSYm23cN_e7Y4ylcK3KHybPFJLfD0ZA",
        "response": true,
        "message": "",
        "errors": []
    }

y el 'result' sera  el token de session

GET http://icsseseguridad.com/api/public/auth/verify

    recibe por la cabezera (header) el token como:
    * APP-TOKEN
    y reporte con el usuario


# * Vehiculos (Vehicles)
(Vehiculos proporcianados por claro-flatas)

GET http://icsseseguridad.com/api/public/vehicle

    Obtiene la lista de vehiculos con su ultima ubicacion

GET http://icsseseguridad.com/api/public/vehicle/{imei}

    Obtiene el vehiculo por el imei

GET http://icsseseguridad.com/api/public/vehicle/history/{imei}

    Obtiene el historial del vehiculo con el imei del dia actual

GET http://icsseseguridad.com/api/public/vehicle/history/{imei}/{year}/{month}/{day}

    Obtiene el historial del vehiculo con el imei del dia seleccionado


# * Guardias (Watches)

* El estatus 1 significa guardia activa
* El estatus 0 significa guardia finalizada


    {
        "id": "10",
        "guard_id": "5",
        "guard_out_id": null,
        "create_date": "2018-08-03 16:23:43",
        "update_date": "2018-08-03 16:23:43",
        "latitude": "10.1176433",
        "longitude": "-68.0477509",
        "observation": "Extravio de clave",
        "status": "1"  <------ Estatus
    }

GET http://icsseseguridad.com/api/public/watch/active/1

    Obtiene todas las watches activas

GET http://icsseseguridad.com/api/public/watch

    Obtiene todas las guardias hechas

GET http://icsseseguridad.com/api/public/watch/{id}

    Obtiene una guardia por su id
    
GET http://icsseseguridad.com/api/public/watch/date/today

    Obtiene todas las guardias del dia actual

GET http://icsseseguridad.com/api/public/watch/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    Obtiene todas las guardias del dia seleccionado
    
GET http://icsseseguridad.com/api/public/watch/guard/{id}

    Obtiene todas las guardias por el id del guardia
    
GET http://icsseseguridad.com/api/public/watch/guard/{id}/active/1

    Obtiene todas las guardias activas por el id del guardia

GET http://icsseseguridad.com/api/public/watch/guard/{id}/date

    Obtiene todas las guardias por el id del guardia del dia actual

GET http://icsseseguridad.com/api/public/watch/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    Obtiene todas las guardias por el id del guardia del dia seleccionado
    
GET http://icsseseguridad.com/api/public/watch/{id}/history

    Obtiene todas los registro de una guardia

POST http://icsseseguridad.com/api/public/watch/start

    Registrar (inicia) una guardia, recibe parametros en el body:
    * guard_id
    * tablet_id
    * stand_id
    * latitude
    * longitude
    * observation (opcional)

PUT http://icsseseguridad.com/api/public/watch/{watch_id}/end

    Finaliza una guardia por el id, recibe parametros en el body:
    * f_latitude
    * f_longitude

# * Empleados (Guards)

GET http://icsseseguridad.com/api/public/guard

    obtiene la lista de empleados
    
GET http://icsseseguridad.com/api/public/guard/active/1

    obtiene la lista de empleados activos
    
GET http://icsseseguridad.com/api/public/guard/active/1

    obtiene la lista de empleados desactivados

GET http://icsseseguridad.com/api/public/guard/{id}

    obtiene un empleado por su id
    
GET http://icsseseguridad.com/api/public/guard/dni/{dni}

    obtiene un empleado por la cedula
    
POST http://icsseseguridad.com/api/public/guard 

    Registrar un empleado, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password
    
PUT http://icsseseguridad.com/api/public/guard/{id} 

    Edita un empleado, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password (opcional)
    
PUT http://icsseseguridad.com/api/public/guard/{id}/photo

    Edita un empleado, recibe en el body solo:
    * photo (url)
    
PUT http://icsseseguridad.com/api/public/guard/{id}/active/0 

    Desactiva un guardia
    
PUT http://icsseseguridad.com/api/public/guard/{id}/active/1

    Activa un guardia

DELETE http://icsseseguridad.com/api/public/guard/{id}

    Elimina un empleado por el id, si el guardia tiene elementos asociados 
    no se podra borrar y se desactivara, y la respuesta seguiria siendo OK
    pero retornara en message la clave "DISABLED".
    
# * Administradores (admins)

GET http://icsseseguridad.com/api/public/admin

    obtiene la lista de administradores
    
GET http://icsseseguridad.com/api/public/admin/active/1

    obtiene la lista de administradores activos

GET http://icsseseguridad.com/api/public/admin/{id}

    obtiene un administrador por su id
    
GET http://icsseseguridad.com/api/public/admin/dni/{dni}

    obtiene un administrador por la cedula
    
POST http://icsseseguridad.com/api/public/admin 

    Registrar un administrador, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password
    
PUT http://icsseseguridad.com/api/public/admin/{id} 

    Edita un administrador, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password (opcional)
    
PUT http://icsseseguridad.com/api/public/admin/{id}/photo

    Edita un empleado, recibe en el body solo:
    * photo (url)
    
PUT http://icsseseguridad.com/api/public/admin/{id}/active/0 

    Desactiva un administrador
    
PUT http://icsseseguridad.com/api/public/admin/{id}/active/1

    Activa un administrador

DELETE http://icsseseguridad.com/api/public/admin/{id}

    Elimina un administrador por el id, si el administrador tiene elementos asociados 
    no se podra borrar y se desactivara, y la respuesta seguiria siendo OK
    pero retornara en el message la clave "DISABLED".
    
# * Incidencias (incidences) [son los tipos de indicencias]

GET http://icsseseguridad.com/api/public/incidence

    obtiene la lista de incidencias

GET http://icsseseguridad.com/api/public/incidence/{id}

    obtiene una incidencia por su id
    
GET http://icsseseguridad.com/api/public/incidence/name/{name}

    obtiene una incidencia por su nombre
    
POST http://icsseseguridad.com/api/public/incidence 

    Registrar una incidencia, recibe parametros en el body:
    * name
    * level
    
PUT http://icsseseguridad.com/api/public/incidence/{id} 

    Edita una incidencia, recibe parametros en el body:
    * name
    * level

DELETE http://icsseseguridad.com/api/public/incidence/{id}

    Elimina una incidencia por el id
    
# * Reporte Especial (special_report)

* El estatus 1 significa que aun no se ha aceptado la notificacion 
* El estatus 2 significa que ya se ha aceptado la notificacion

GET http://icsseseguridad.com/api/public/binnacle

    obtiene la lista de reportes
    
GET http://icsseseguridad.com/api/public/binnacle/{id}

    obtiene un reporte por su id
    
GET http://icsseseguridad.com/api/public/binnacle/active/1

    obtiene la lista de reportes a los cuales no se le ha aceptado la notificacion
    
Sobre el estado de resuelto (resolved)
    
    Ahora todas las consultas requieren especificar el resolved
    
    Hay 5 especificaciones posibles para el resolved:
    
        resolved = all  -> todos 
        resolved = open -> abierto y reabiertos (1,2)
        resolved = 0    -> cerrados 
        resolved = 1    -> abiertos 
        resolved = 2    -> reabiertos 
    
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}

    obtiene la lista de reportes del dia actual
    
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date

    obtiene la lista de reportes del dia actual
    
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de reportes del dia seleccionado
    
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/incidence/{id}     <- by Incidence
 
     obtiene todas los reportes registrados de un tipo incidence por el id
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/incidence/{id}/date
 
     obtiene todas los reportes registrados de un tipo incidence por el id del dia actual
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/incidence/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas los reportes registrados de un tipo incidence por el id del dia seleccionado
         
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/guard/{id}     <- by Guard
 
     obtiene todas los reportes registrados de un empleado por el id
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/guard/{id}/date
 
     obtiene todas las visitas registrados de un empleado por el id del dia actual
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registrados de un empleado por el id del dia seleccionado
    
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/watch/{id}     <- by Watch
 
     obtiene todas los reportes registrados en una guardia por el id
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/watch/{id}/date
 
     obtiene todas las visitas registrados en una guardia por el id del dia actual
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/watch/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registrados en una guardia por el id del dia seleccionado
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}     <- by resolved
 
     obtiene todas los reportes registrados en estado de su resolucion
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date
 
     obtiene todas los reportes registrados en estado de su resolucion del dia actual
     
GET http://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas los reportes registrados en estado de su resolucion del dia seleccionado
    
GET http://icsseseguridad.com/api/public/binnacle/{id}/replies

    obtiene la lista de comentarios hechos a un reporte
    
POST http://icsseseguridad.com/api/public/binnacle/binnacle 

    Registrar un reporte, recibe parametros en el body:
    * incidence_id
    * watch_id
    * title
    * observation
    * latitude
    * longitude
    
PUT http://icsseseguridad.com/api/public/binnacle/accept/{id} 

    Pasa el status del reporte a 2, que quiere decir que ya se acepto la notificacion, 
    no recibe parametros.
    0 -> Eliminado
    1 -> Creado
    2 -> Aceptado
    
PUT http://icsseseguridad.com/api/public/binnacle/resolved/{id} 

    Pasa el estado resuelto a 0, 
    0 -> resuelto.
    1 -> Caso Abierto
    2 -> Caso Reabierto
    
PUT http://icsseseguridad.com/api/public/binnacle/open/{id} 

    Pasa el estado resuelto a 2, (reabierto) 
  
DELETE http://icsseseguridad.com/api/public/binnacle/{id}
(no se usara el delete, los reportes creados no se pueden eliminar)

    Elimina el reporte por el id
    
# * Comentarios al reporte especial (replies)

GET http://icsseseguridad.com/api/public/binnacle-reply

    obtiene la lista de comentarios

GET http://icsseseguridad.com/api/public//binnacle-reply/{id}

    obtiene un comentario por su id
    
POST http://icsseseguridad.com/api/public//binnacle-reply

    Registrar un comentario, recibe parametros en el body:
    enviar admin_id si comenta un administrador, enviar guard_id si comenta un empleado
    * report_id
    * text
    * user_name (nombre completo del que hace el comentario)
    * admin_id (obligatorio si no tiene guard_id) 
    * guard_id (obligatorio si no tiene admin_id) 
    

DELETE http://icsseseguridad.com/api/public/binnacle-reply/{id} 

    Elimina un comentario por el id
    
# * Visitantes (Visitors)

GET http://icsseseguridad.com/api/public/visitor

    obtiene la lista de visitantes
    
GET http://icsseseguridad.com/api/public/visitor/active/1

    obtiene la lista de visitantes activos

GET http://icsseseguridad.com/api/public/visitor/{id}

    obtiene un visitante por su id
    
GET http://icsseseguridad.com/api/public/visitor/dni/{dni}

    obtiene un visitante por la cedula
    
POST http://icsseseguridad.com/api/public/visitor 

    Registrar un visitante, recibe parametros en el body:
    * dni
    * name
    * lastname
    * company (opcional)
    * photo (opcional)(url)
   
    
PUT http://icsseseguridad.com/api/public/visitor/{id} 

    Actualiza un visitante, recibe parametros en el body:
    * dni
    * name
    * lastname
    * company (opcional)
    * photo (opcional)(url)

DELETE http://icsseseguridad.com/api/public/visitor/{id}

    Elimina un visitante por el id
    
# * Funcionario (Clerks)

GET http://icsseseguridad.com/api/public/clerk

    obtiene la lista de funcionarios
    
GET http://icsseseguridad.com/api/public/clerk/active/1

    obtiene la lista de funcionarios activos

GET http://icsseseguridad.com/api/public/clerk/{id}

    obtiene un funcionario por su id
    
GET http://icsseseguridad.com/api/public/clerk/dni/{dni}

    obtiene un funcionario por la cedula
    
POST http://icsseseguridad.com/api/public/clerk 

    Registrar un funcionario, recibe parametros en el body:
    * dni
    * name
    * lastname
    * address (opcional)
   
    
PUT http://icsseseguridad.com/api/public/clerk/{id} 

    Actualiza un funcionario, recibe parametros en el body:
    * dni
    * name
    * lastname
    * address (opcional)

DELETE http://icsseseguridad.com/api/public/clerk/{id}

    Elimina un funcionario por el id
    
# * Vehiculo Visitante (Visitor vehicle)

GET http://icsseseguridad.com/api/public/visitor-vehicle

    obtiene la lista de vehiculos visitantes
    
GET http://icsseseguridad.com/api/public/visitor-vehicle/active/1

    obtiene la lista de vehiculos visitantes activos

GET http://icsseseguridad.com/api/public/visitor-vehicle/{id}

    obtiene un vehiculo vitante por su id
    
GET http://icsseseguridad.com/api/public/visitor-vehicle/plate/{plate}

    obtiene un vehiculo vitante por la placa
    
POST http://icsseseguridad.com/api/public/visitor-vehicle/register 

    Registrar un vehiculo vitante, recibe parametros en el body:
    * plate
    * model
    * type
    * vehicle (opcional)
    * photo (opcional)
   
    
PUT http://icsseseguridad.com/api/public/visitor-vehicle/{id} 

    Actualiza un vehiculo vitante, recibe parametros en el body:
    * plate
    * model
    * type
    * vehicle (opcional)
    * photo (opcional)

DELETE http://icsseseguridad.com/api/public/visitor-vehicle/{id}

    Elimina un vehiculo vitante por el id
    
# * Visita (visit)
    
GET http://icsseseguridad.com/api/public/visit/active/1

    obtiene la lista de visitas activas

GET http://icsseseguridad.com/api/public/visit/{id}

    obtiene una visita por su id
    
Ahora las visitas requieren el status en la url
    status = 1     -> visitas activas
    status = 0     -> visitas finalizadas
    status = all   -> todos los status
    
GET http://icsseseguridad.com/api/public/visit/status/{status}

    obtiene la lista de visitas
    
GET http://icsseseguridad.com/api/public/visit/status/{status}/date
 
     obtiene todas las visitas registradas del dia actual
    
GET http://icsseseguridad.com/api/public/visit/status/{status}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene todas las visitas registradas del rango seleccionado
    
GET http://icsseseguridad.com/api/public/visit/status/{status}/guard/{id}     <- by Guard
 
     obtiene todas las visitas registradas de un empleado por el id
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/guard/{id}/date
 
     obtiene todas las visitas registradas de un empleado por el id del dia actual
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas de un empleado por el id del dia seleccionado
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/vehicle/{id}       <- by Vehicle    
 
     obtiene todas las visitas registradas por el id del vehiculo
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/vehicle/{id}/date
 
     obtiene todas las visitas registradas por el id del vehiculo del dia actual
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/vehicle/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas por el id del vehiculo del dia seleccionado
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/visitor/{id}       <- by Visitor
 
     obtiene todas las visitas registradas por el id del visitante
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/visitor/{id}/date
 
     obtiene todas las visitas registradas por el id del visitante del dia actual
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/visitor/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas por el id del visitante del dia seleccionado
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/clerk/{id}     <- by Clerk
 
     obtiene todas las visitas registradas por el id del funcionario visitado
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/clerk/{id}/date
 
     obtiene todas las visitas registradas por el id del funcionario visitado del dia actual
     
GET http://icsseseguridad.com/api/public/visit/status/{status}/clerk/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas por el id del funcionario visitado del dia seleccionado
    
POST http://icsseseguridad.com/api/public/visit 

    Registrar una visita, recibe parametros en el body:
    * visitor_id
    * guard_id
    * vehicle_id (opcional)
    * visited_id (opcional)
    * persons
    * latitude
    * longitude
    * observation (opcional)
   
PUT http://icsseseguridad.com/api/public/visit/{id} 

    Finaliza la visita por el id

DELETE http://icsseseguridad.com/api/public/visit/{id}

    Elimina una visita por el id
    
# * Alerta (alert)

GET http://icsseseguridad.com/api/public/alert

    obtiene la lista de alertas
    
GET http://icsseseguridad.com/api/public/alert/active/1

    obtiene la lista de alertas activas

GET http://icsseseguridad.com/api/public/alert/{id}

    obtiene una alerta por su id
    
GET http://icsseseguridad.com/api/public/alert/cause/{cause}

    obtiene la lista de alertas por su causa
    
GET http://icsseseguridad.com/api/public/alert/cause/{cause}/date

    obtiene la lista de alertas por su causa del dia actual
    
GET http://icsseseguridad.com/api/public/alert/cause/{cause}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de alertas por su causa del dia seleccionado
    
GET http://icsseseguridad.com/api/public/alert/cause/{cause}/guard/{id}

    obtiene la lista de alertas por su causa y id del guardia
    
GET http://icsseseguridad.com/api/public/alert/cause/{cause}/guard/{id}/date

    obtiene la lista de alertas por su causa y id del guardia del dia actual
    
GET http://icsseseguridad.com/api/public/alert/cause/{cause}/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de alertas por su causa y id del guardia del dia seleccionado
    
POST http://icsseseguridad.com/api/public/alert 

    Registrar una visita, recibe parametros en el body:
    * guard_id
    * cause
    * extra (opcional)
    * latitude
    * longitude
    
PUT http://icsseseguridad.com/api/public/alert/{id} 

    Pone la alerta como solventada

DELETE http://icsseseguridad.com/api/public/alert/{id}

    Elimina una alerta por el id
    
# * Configuracion (utility)

GET http://icsseseguridad.com/api/public/utility

    obtiene la lista de configuraciones
    

GET http://icsseseguridad.com/api/public/utility/{id}

    obtiene una configuracion por su id
    
GET http://icsseseguridad.com/api/public/utility/name/{name}

    obtiene una configuracion por su nombre
    
POST http://icsseseguridad.com/api/public/alert 

    Registrar una configuracion, recibe parametros en el body:
    * name
    * value
    
PUT http://icsseseguridad.com/api/public/utility/{id} 

    Edita el valor de una configuracion, recibe parametros en el body:
    * value 

DELETE http://icsseseguridad.com/api/public/utility/{id}

    Borra una configuracion
     
# * Messenger (CHAT)

POST http://icsseseguridad.com/api/public/messenger/register/web

    Registrar el token de la pagina web, recibe parametros en el body:
    * registration_id   (token firebase)
    * admin_id
    * session  (identificador de session o ip, esta en veremos el uso) 
    
POST http://icsseseguridad.com/api/public/messenger/register/tablet

    Registrar el token de la los dispositivos, recibe parametros en el body:
    * registration_id   (token firebase)
    * guard_id
    * imei   
  
POST http://icsseseguridad.com/api/public/messenger/chat

    Creacion de chat con otro individuo, recibe parametros en el body:
    * user_1_id         (id del usuario)
    * user_1_type       (tipo de usuario/ ADMIN o GUARD)
    * user_1_name       (nombre completo del usuario)  
    * user_2_id         (id del usuario)
    * user_2_type       (tipo de usuario/ ADMIN o GUARD)
    * user_2_name       (nombre completo del usuario) 
    
POST http://icsseseguridad.com/api/public/messenger/channel

    Creacion de channel para chat grupal y agregar al creador al channel de una vez, 
        recibe parametros en el body:
        
    * name               (nombre del grupo)
    * creator_id         (id del creador)
    * creator_type       (tipo de usuario/ ADMIN o GUARD)
    * creator_name       (nombre completo del creador)  
 
POST http://icsseseguridad.com/api/public/messenger/channel/{channel_id}/add
 
    Agrega usuarios al channel, recibe un array en el body, 
    cada objecto del array debe contener:
         
    * user_id         (id del nuevo miembro)
    * user_type       (tipo de usuario/ ADMIN o GUARD)
    * user_name       (nombre completo del nuevo miembro) 
     
POST http://icsseseguridad.com/api/public/messenger/send

    Envio de mensaje al otro usuario, recibe parametros en el body: 
    * text              (mensaje que se envia)
    * chat_id           (id del chat si el mensaje pertenece a un chat)
    * channel_id        (id del channel si el mensaje pertenece a un chat grupal)
    * sender_id         (id del usuario que envia)
    * sender_type       (tipo de usuario que envia. ADMIN o GUARD)
    * sender_name       (nombre completo del que envia)
    
GET http://icsseseguridad.com/api/public/messenger/conversations/admin/{id}
 
     obtiene todas los chat abiertos de un administrador por su id
     
GET http://icsseseguridad.com/api/public/messenger/conversations/guard/{id}
 
     obtiene todas los chat abiertos de un guardia por su id
     
GET http://icsseseguridad.com/api/public/messenger/conversations/chat/{id}
 
     obtiene todos los mensajes de una conversacion atravez del id del chat
     
GET http://icsseseguridad.com/api/public/messenger/channel/guard/{id}
 
     obtiene todos los channel al que esta suscrito el guardia
     
GET http://icsseseguridad.com/api/public/messenger/channel/admin/{id}
 
     obtiene todos los channel al que esta suscrito el administrador
     
GET http://icsseseguridad.com/api/public/messenger/channel/{id}/members
 
     obtiene todos los miembros de un channel
     
GET http://icsseseguridad.com/api/public/messenger/conversations/channel/{id}
 
     obtiene todos los mensajes de un channel
     
GET http://icsseseguridad.com/api/public/messenger/conversations/admin/{admin_id}/chat/unread

    obtiene las cantida de mensajes sin leer del administrador y los chat que tienen dichos mensajes sin leer
    
    ejemplo:
        
        {
            "unread": 2,
            "data": [
                {
                    "chat": {
                        "id": "52",
                        "user_1_id": "9",
                        "user_1_type": "ADMIN",
                        "user_1_name": "Jose Alonzo Palacios Blanco",
                        "user_2_id": "2",
                        "user_2_type": "GUARD",
                        "user_2_name": "Guardia 2",
                        "create_at": "2018-09-08 18:25:02",
                        "update_at": "2018-09-08 18:27:35",
                        "state": "1"
                    },
                    "unread": 2
                }
            ]
        }
        
GET http://icsseseguridad.com/api/public/messenger/conversations/guard/{guard_id}/chat/unread

    obtiene las cantida de mensajes sin leer del administrador y los chat que tienen dichos mensajes sin leer
    
PUT http://icsseseguridad.com/api/public/messenger/conversations/admin/{admin_id}/chat/{chat_id}/read

    marca todos los mensajes de un chat de un administrador como leidos, este metodo debe llamarse siempre al abrir un chat
    
PUT http://icsseseguridad.com/api/public/messenger/conversations/guardia/{guard_id}/chat/{chat_id}/read

    marca todos los mensajes de un chat de un guardia como leidos, este metodo debe llamarse siempre al abrir un chat
     
# * Banner [imagenes para mostrar en el home de las tablets]

GET http://icsseseguridad.com/api/public/banner

    obtiene la lista de banners

GET http://icsseseguridad.com/api/public/banner/{id}

    obtiene un banner por su id
   
POST http://icsseseguridad.com/api/public/banner 

    Registrar una banner, recibe parametro en el body:
    * photo (url)

DELETE http://icsseseguridad.com/api/public/banner/{id}

    Elimina una banner por el id
    
# * Bounds 

    Los vehiculos solo pueden estar en un cerco a la vez

GET http://icsseseguridad.com/api/public/bounds

    obtiene todas los cercos virutales
   
POST http://icsseseguridad.com/api/public/bounds 

    Registrar un cerco virutal, recibe parametro en el body:
    * name
    * color (deberia ser en hexadecimal)
    * points (array string de puntos del poligono)

PUT http://icsseseguridad.com/api/public/bounds/{id}

    Edita un cerco virutal, recibe parametro en el body:
    * name
    * color (deberia ser en hexadecimal)
    * points (array string de puntos del poligono)
    
DELETE http://icsseseguridad.com/api/public/bounds/{id}

    Elimina un cerco virutal.
    
POST http://icsseseguridad.com/api/public/bounds/{id}/vehicle

    Agrega vehiculos al cerco virtual, recibe por el bodi un array en string de "imei"
    
    ejemplo: [
             	{
             		"imei": "867162025555004"
             	},
             	{
             		"imei": "862366030046173"
             	},
             	{
             		"imei": "860599001483437"
             	}
             ]
             
DELETE http://icsseseguridad.com/api/public/bounds/vehicle/{vehicle_id}

    quita un vehiculo del cerco virtual
    
GET http://icsseseguridad.com/api/public/bounds/{id}/vehicle

    obtiene todas los vehiculos asociados a un cerco virtual
    
# * Bounds Groups (Grupos para cercos virtuales)

POST http://icsseseguridad.com/api/public/bounds_group

    Registrar un grupo, recibe parametro en el body:
    * name
    
PUT http://icsseseguridad.com/api/public/bounds_group/{bounds_group_id}

    Edita el grupo seleccionado, recibe parametro en el body:
    * name
    
DELETE http://icsseseguridad.com/api/public/bounds_group/{bounds_group_id}

    Borrar un grupo, si no se puede borrar se desactiva
    
GET http://icsseseguridad.com/api/public/bounds_group

    obtiene todos los grupos
    
POST http://icsseseguridad.com/api/public/bounds_group/{bounds_group_id}/bounds/add

    Agrega cercos virtuales al grupo, recibe por el body un array en string de "id" de los cercos
    
    ejemplo: [
             	{
             		"id": 1
             	},
             	{
             		"id": 2
             	},
             	{
             		"id": 3
             	}
             ]
             
GET http://icsseseguridad.com/api/public/bounds/group/{bounds_group_id}

    obtiene todas los cerco por el grupo 
    
PUT http://icsseseguridad.com/api/public/bounds/{bounds_id}/group/remove

    remueve el cerco del grupo
    
# * Tablets

POST http://icsseseguridad.com/api/public/tablet

    Registrar una tablet, recibe parametro en el body:
    * imei
    
PUT http://icsseseguridad.com/api/public/tablet/{tablet_id}/active/1

    activa una tablet
    
PUT http://icsseseguridad.com/api/public/tablet/{tablet_id}/active/0

    desactiva una tablet
    
GET http://icsseseguridad.com/api/public/tablet/active/{status}

    obtiene la lista de tablets por el estado, 
    all => Todas
    1   => Activas
    0   => Desactivadas
    
DELETE http://icsseseguridad.com/api/public/tablet/{tablet_id}

    Borrar la tablet por el id, si esta asociada solo la desactiva
    
   
# * Stands

POST http://icsseseguridad.com/api/public/stand

    Registrar un puesto para las tablets, recibe parametro en el body:
    * name
    * address
    
PUT http://icsseseguridad.com/api/public/stand/{stand_id}

    Edita el puesto seleccionado, recibe parametro en el body:
    * name
    * address
    
DELETE http://icsseseguridad.com/api/public/stand/{stand_id}

    Borrar un puesto
    
GET http://icsseseguridad.com/api/public/stand

    obtiene todos los puestos
    
POST http://icsseseguridad.com/api/public/stand/{id}/tablet/add

    Agrega tablets al puesto, recibe por el body un array en string de "id" de tablets
    
    ejemplo: [
             	{
             		"id": 1
             	},
             	{
             		"id": 2
             	},
             	{
             		"id": 3
             	}
             ]
             
POST http://icsseseguridad.com/api/public/stand/{id}/guard/add

    Agrega guardias al puesto, recibe por el body un array en string de "id" de guardias
    
    ejemplo: [
             	{
             		"id": 1
             	},
             	{
             		"id": 2
             	},
             	{
             		"id": 3
             	}
             ]    
   
GET http://icsseseguridad.com/api/public/tablet/stand/{stand_id}

    obtiene todas las tablets asociadas a un puesto
    
GET http://icsseseguridad.com/api/public/guard/stand/{stand_id}
    
    obtiene todas los guardias asociadas a un puesto
    
PUT http://icsseseguridad.com/api/public/guard/{guard_id}/stand/remove

    remueve el guardia del puesto
    
PUT http://icsseseguridad.com/api/public/tablet/{tablet_id}/stand/remove

    remueve la tablet del puesto
    
    
# * Position de las Tablets (tablet-position)

GET http://icsseseguridad.com/api/public/tablet

    Obtiene el ultimo registro de cada tablet

POST http://icsseseguridad.com/api/public/tablet/position

    Registrar la posicion, recibe parametros en el body:
    * latitude
    * longitude
    * watch_id
    * imei
    * message
    
GET http://icsseseguridad.com/api/public/tablet/all

    obtiene todas las posiciones registrar (usar solo para pruebas, no poner en produccion)
    
GET http://icsseseguridad.com/api/public/tablet/id/{id}
 
     obtiene una posicion por su id
    
GET http://icsseseguridad.com/api/public/tablet/date/today
 
     obtiene todas las posiciones registradas del dia actual
    
GET http://icsseseguridad.com/api/public/tablet/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas del periodo seleccionado
     
GET http://icsseseguridad.com/api/public/tablet/watch/{id}
 
     obtiene todas las posiciones registradas de una guardia por el id
     
GET http://icsseseguridad.com/api/public/tablet/watch/{id}/date
 
     obtiene todas las posiciones registradas de una guardia por el id del dia actual
     
GET http://icsseseguridad.com/api/public/tablet/watch/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas de una guardia por el id del periodo seleccionado
     
GET http://icsseseguridad.com/api/public/tablet/guard/{id}
 
     obtiene todas las posiciones registradas de un empleado por el id
     
GET http://icsseseguridad.com/api/public/tablet/guard/{id}/date
 
     obtiene todas las posiciones registradas de un empleado por el id del dia actual
     
GET http://icsseseguridad.com/api/public/tablet/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas de un empleado por el id del periodo seleccionado
     
GET http://icsseseguridad.com/api/public/tablet/imei/{imei}
 
     obtiene todas las posiciones registradas de una tablet por su imei
     
GET http://icsseseguridad.com/api/public/tablet/imei/{imei}/date
 
     obtiene todas las posiciones registradas de una tablet por su imei del dia actual
     
GET http://icsseseguridad.com/api/public/tablet/imei/{imei}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas de una tablet por su imei del periodo seleccionado
     
GET http://icsseseguridad.com/api/public/tablet/message/{message}
 
     obtiene todas las posiciones registradas con ese message
     
GET http://icsseseguridad.com/api/public/tablet/message/{message}/date
 
     obtiene todas las posiciones registradas con ese message del dia actual
     
GET http://icsseseguridad.com/api/public/tablet/message/{message}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas con ese message del periodo seleccionado
     
# * Salidas del cerco virtual, Zonas

    formato de respuesta: array que contiene:
    
    "id": "3",
    "guard_id": null,
    "imei": "862894022745478",
    "cause": "OUT_BOUNDS",
    "type": "OUT_BOUNDS",
    "message": "Guardia 2 Guardia 2 ha iniciado su guardia",
    "extra": null,
    "latitude": "10.123456",
    "longitude": "60.123456",
    "create_date": "2018-08-30 22:16:33",
    "update_date": "2018-08-30 22:16:33",
    "status": "0",
    "alias": "HALCON 01 INGENIO VALDEZ",
    "in": {
        "id": "4",
        "guard_id": null,
        "imei": "862894022745478",
        "cause": "OUT_BOUNDS",
        "type": "IN_BOUNDS",
        "message": "El vehiculo HALCON 01 INGENIO VALDEZ fue encendido",
        "extra": null,
        "latitude": "-2.021347",
        "longitude": "-79.590240",
        "create_date": "2018-09-02 06:31:19",
        "update_date": "2018-09-02 06:31:19",
        "status": "0"
    },
    "diff_sec": 202486,
    "diff_text": "2 Dias"
    
    datos que deberian mostrarse:
    
        imei                (imei del vehiculo)
        alias               (alias del vehiculo)
        create_date:        (hora en que salio del la zona)
        latitude            (coordenadas en las que salio del zona)
        longitude           (coordenadas en las que salio del zona)
        in.create_date      (hora en que volvio a la zona)
        in.latitude         (coordenadas en las que volvio al cerco)
        in.longitude        (coordenadas en las que volvio al cerco)
        diff_text           (duracion que estuvo fuera del cero)
        
    si el objecto "in" no existe, significa que aun esta fuera del cerco, esto debe connotarse y en vez de mostrar
    in.create_date, in.latitude, in.longitude, debe mostrar (AUN FUERA DE LA ZONA ESTABLECIDA)

GET http://icsseseguridad.com/api/public/alert/out/bounds

    obtiene la lista de salidas del cerco
    
GET http://icsseseguridad.com/api/public/alert/out/bounds/date

    obtiene la lista de salidas del cerco del dia actual
    
GET http://icsseseguridad.com/api/public/alert/out/bounds/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de salidas del cerco del rango seleccionado
    
GET http://icsseseguridad.com/api/public/alert/out/bounds/imei/{imei}

    obtiene la lista de salidas del cerco de un vehiculos a tra vez de su imei
    
GET http://icsseseguridad.com/api/public/alert/out/bounds/imei/{imei}/date

    obtiene la lista de salidas del cerco del dia actual de un vehiculos a tra vez de su imei
    
GET http://icsseseguridad.com/api/public/alert/out/bounds/imei/{imei}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de salidas del cerco del rango seleccionado de un vehiculos a tra vez de su imei


