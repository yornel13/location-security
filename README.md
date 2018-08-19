# REST API

* Url basico http://icsseseguridad.com/location-security/public/
* la api acepta consultas GET, POST, PUT y DELETE  segun sea el caso
* todas las respuesta son en formato JSON
* todas las consulta con POST y PUT el body va con formato JSON
* Usar Postman, Restlet Client, o alguna otra extension de chrome para probar las url
* Probar los GET, POST, PUT, DELETE para entender los resultados y trabajar con ellos
* En las url, lo que este entre {} es por lo que debe remplazar. Ejemplo:

"http://icsseseguridad.com/location-security/public/vehicle/{imei}"

se rempleaza por 

"http://icsseseguridad.com/location-security/public/vehicle/867162025555236"

los GET responden con la lista de objectos solicitados y el total

    http://icsseseguridad.com/location-security/public/incidence
    
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

    http://icsseseguridad.com/location-security/public/incidence/5
    
    {
        "id": "5",
        "name": "Otro"
    }

Si la solicitud get al objecto especifico no arroja ninguna resultado la respuesta sera un simple false
    
    http://icsseseguridad.com/location-security/public/incidence/58
    
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


Recomendacion: Se deberia agregar una variable estatica de sistema con el siguiente valor = http://icsseseguridad.com/location-security/public/ y agregarle el path
asi en caso de cambiar la url del servidor solo de modificara la variable estatica.

# Logeo (auth)
POST http://icsseseguridad.com/location-security/public/auth/admin

    logeo de administradores, recibe por body
    * dni
    * password
    
    
POST http://icsseseguridad.com/location-security/public/auth/guard

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

GET http://icsseseguridad.com/location-security/public/auth/verify

    recibe por la cabezera (header) el token como:
    * APP-TOKEN
    y reporte con el usuario


# * Vehiculos (Vehicles)
(Vehiculos proporcianados por claro-flatas)

GET http://icsseseguridad.com/location-security/public/vehicle

    Obtiene la lista de vehiculos con su ultima ubicacion

GET http://icsseseguridad.com/location-security/public/vehicle/{imei}

    Obtiene el vehiculo por el imei

GET http://icsseseguridad.com/location-security/public/vehicle/history/{imei}

    Obtiene el historial del vehiculo con el imei del dia actual

GET http://icsseseguridad.com/location-security/public/vehicle/history/{imei}/{year}/{month}/{day}

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

GET http://icsseseguridad.com/location-security/public/watch/active/1

    Obtiene todas las watches activas

GET http://icsseseguridad.com/location-security/public/watch

    Obtiene todas las guardias hechas (deberia tener un filtro por dia que aun no se ha hecho)

GET http://icsseseguridad.com/location-security/public/watch/{id}

    Obtiene una guardia por su id

POST http://icsseseguridad.com/location-security/public/watch

    Registrar (inicia) una guardia, recibe parametros en el body:
    * guard_id
    * latitude
    * longitude
    * observation (opcional)

PUT http://icsseseguridad.com/location-security/public/watch/{watch_id}

    Finaliza una guardia por el id, recibe parametros en el body:
    * latitude
    * longitude
    * observation (opcional)

# * Empleados (Guards)

GET http://icsseseguridad.com/location-security/public/guard

    obtiene la lista de empleados
    
GET http://icsseseguridad.com/location-security/public/guard/active/1

    obtiene la lista de empleados activos
    
GET http://icsseseguridad.com/location-security/public/guard/active/1

    obtiene la lista de empleados desactivados

GET http://icsseseguridad.com/location-security/public/guard/{id}

    obtiene un empleado por su id
    
GET http://icsseseguridad.com/location-security/public/guard/dni/{dni}

    obtiene un empleado por la cedula
    
POST http://icsseseguridad.com/location-security/public/guard 

    Registrar un empleado, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password
    
PUT http://icsseseguridad.com/location-security/public/guard/{id} 

    Edita un empleado, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password (opcional)
    
PUT http://icsseseguridad.com/location-security/public/guard/{id}/photo

    Edita un empleado, recibe en el body solo:
    * photo (url)
    
PUT http://icsseseguridad.com/location-security/public/guard/{id}/active/0 

    Desactiva un guardia
    
PUT http://icsseseguridad.com/location-security/public/guard/{id}/active/1

    Activa un guardia

DELETE http://icsseseguridad.com/location-security/public/guard/{id}

    Elimina un empleado por el id, si el guardia tiene elementos asociados 
    no se podra borrar y se desactivara, y la respuesta seguiria siendo OK
    pero retornara en message la clave "DISABLED".
    
# * Administradores (admins)

GET http://icsseseguridad.com/location-security/public/admin

    obtiene la lista de administradores
    
GET http://icsseseguridad.com/location-security/public/admin/active/1

    obtiene la lista de administradores activos

GET http://icsseseguridad.com/location-security/public/admin/{id}

    obtiene un administrador por su id
    
GET http://icsseseguridad.com/location-security/public/admin/dni/{dni}

    obtiene un administrador por la cedula
    
POST http://icsseseguridad.com/location-security/public/admin 

    Registrar un administrador, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password
    
PUT http://icsseseguridad.com/location-security/public/admin/{id} 

    Edita un administrador, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password (opcional)
    
PUT http://icsseseguridad.com/location-security/public/admin/{id}/photo

    Edita un empleado, recibe en el body solo:
    * photo (url)

DELETE http://icsseseguridad.com/location-security/public/admin/{id}

    Elimina un administrador por el id
    
# * Incidencias (incidences) [son los tipos de indicencias]

GET http://icsseseguridad.com/location-security/public/incidence

    obtiene la lista de incidencias

GET http://icsseseguridad.com/location-security/public/incidence/{id}

    obtiene una incidencia por su id
    
GET http://icsseseguridad.com/location-security/public/incidence/name/{name}

    obtiene una incidencia por su nombre
    
POST http://icsseseguridad.com/location-security/public/incidence 

    Registrar una incidencia, recibe parametros en el body:
    * name
    * level
    
PUT http://icsseseguridad.com/location-security/public/incidence/{id} 

    Edita una incidencia, recibe parametros en el body:
    * name
    * level

DELETE http://icsseseguridad.com/location-security/public/incidence/{id}

    Elimina una incidencia por el id
    
# * Reporte Especial (special_report)

* El estatus 1 significa que aun no se ha aceptado la notificacion 
* El estatus 2 significa que ya se ha aceptado la notificacion

GET http://icsseseguridad.com/location-security/public/binnacle

    obtiene la lista de reportes
    
GET http://icsseseguridad.com/location-security/public/binnacle/{id}

    obtiene un reporte por su id
    
GET http://icsseseguridad.com/location-security/public/binnacle/active/1

    obtiene la lista de reportes a los cuales no se le ha aceptado la notificacion
    
Sobre el estado de resuelto (resolved)
    
    Ahora todas las consultas requieren especificar el resolved
    
    Hay 5 especificaciones posibles para el resolved:
    
        resolved = all  -> todos 
        resolved = open -> abierto y reabiertos (1,2)
        resolved = 0    -> cerrados 
        resolved = 1    -> abiertos 
        resolved = 2    -> reabiertos 
    
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}

    obtiene la lista de reportes del dia actual
    
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/date

    obtiene la lista de reportes del dia actual
    
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/date/{year}/{month}/{day}

    obtiene la lista de reportes del dia seleccionado
    
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/incidence/{id}     <- by Incidence
 
     obtiene todas los reportes registrados de un tipo incidence por el id
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/incidence/{id}/date
 
     obtiene todas los reportes registrados de un tipo incidence por el id del dia actual
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/incidence/{id}/date/{year}/{month}/{day}
 
     obtiene todas los reportes registrados de un tipo incidence por el id del dia seleccionado
         
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/guard/{id}     <- by Guard
 
     obtiene todas los reportes registrados de un empleado por el id
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/guard/{id}/date
 
     obtiene todas las visitas registrados de un empleado por el id del dia actual
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/guard/{id}/date/{year}/{month}/{day}
 
     obtiene todas las visitas registrados de un empleado por el id del dia seleccionado
    
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/watch/{id}     <- by Watch
 
     obtiene todas los reportes registrados en una guardia por el id
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/watch/{id}/date
 
     obtiene todas las visitas registrados en una guardia por el id del dia actual
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/watch/{id}/date/{year}/{month}/{day}
 
     obtiene todas las visitas registrados en una guardia por el id del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}     <- by resolved
 
     obtiene todas los reportes registrados en estado de su resolucion
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/date
 
     obtiene todas los reportes registrados en estado de su resolucion del dia actual
     
GET http://icsseseguridad.com/location-security/public/binnacle/resolved/{resolved}/date/{year}/{month}/{day}
 
     obtiene todas los reportes registrados en estado de su resolucion del dia seleccionado
    
GET http://icsseseguridad.com/location-security/public/binnacle/{id}/replies

    obtiene la lista de comentarios hechos a un reporte
    
POST http://icsseseguridad.com/location-security/public/binnacle/binnacle 

    Registrar un reporte, recibe parametros en el body:
    * incidence_id
    * watch_id
    * title
    * observation
    * latitude
    * longitude
    
PUT http://icsseseguridad.com/location-security/public/binnacle/accept/{id} 

    Pasa el status del reporte a 2, que quiere decir que ya se acepto la notificacion, 
    no recibe parametros.
    0 -> Eliminado
    1 -> Creado
    2 -> Aceptado
    
PUT http://icsseseguridad.com/location-security/public/binnacle/resolved/{id} 

    Pasa el estado resuelto a 0, 
    0 -> resuelto.
    1 -> Caso Abierto
    2 -> Caso Reabierto
    
PUT http://icsseseguridad.com/location-security/public/binnacle/open/{id} 

    Pasa el estado resuelto a 2, (reabierto) 
  
DELETE http://icsseseguridad.com/location-security/public/binnacle/{id}
(no se usara el delete, los reportes creados no se pueden eliminar)

    Elimina el reporte por el id
    
# * Comentarios al reporte especial (replies)

GET http://icsseseguridad.com/location-security/public/binnacle-reply

    obtiene la lista de comentarios

GET http://icsseseguridad.com/location-security/public//binnacle-reply/{id}

    obtiene un comentario por su id
    
POST http://icsseseguridad.com/location-security/public//binnacle-reply

    Registrar un comentario, recibe parametros en el body:
    enviar admin_id si comenta un administrador, enviar guard_id si comenta un empleado
    * report_id
    * text
    * user_name (nombre completo del que hace el comentario)
    * admin_id (obligatorio si no tiene guard_id) 
    * guard_id (obligatorio si no tiene admin_id) 
    

DELETE http://icsseseguridad.com/location-security/public/binnacle-reply/{id} 

    Elimina un comentario por el id
    
# * Visitantes (Visitors)

GET http://icsseseguridad.com/location-security/public/visitor

    obtiene la lista de visitantes
    
GET http://icsseseguridad.com/location-security/public/visitor/active/1

    obtiene la lista de visitantes activos

GET http://icsseseguridad.com/location-security/public/visitor/{id}

    obtiene un visitante por su id
    
GET http://icsseseguridad.com/location-security/public/visitor/dni/{dni}

    obtiene un visitante por la cedula
    
POST http://icsseseguridad.com/location-security/public/visitor 

    Registrar un visitante, recibe parametros en el body:
    * dni
    * name
    * lastname
    * company (opcional)
    * photo (opcional)(url)
   
    
PUT http://icsseseguridad.com/location-security/public/visitor/{id} 

    Actualiza un visitante, recibe parametros en el body:
    * dni
    * name
    * lastname
    * company (opcional)
    * photo (opcional)(url)

DELETE http://icsseseguridad.com/location-security/public/visitor/{id}

    Elimina un visitante por el id
    
# * Funcionario (Clerks)

GET http://icsseseguridad.com/location-security/public/clerk

    obtiene la lista de funcionarios
    
GET http://icsseseguridad.com/location-security/public/clerk/active/1

    obtiene la lista de funcionarios activos

GET http://icsseseguridad.com/location-security/public/clerk/{id}

    obtiene un funcionario por su id
    
GET http://icsseseguridad.com/location-security/public/clerk/dni/{dni}

    obtiene un funcionario por la cedula
    
POST http://icsseseguridad.com/location-security/public/clerk 

    Registrar un funcionario, recibe parametros en el body:
    * dni
    * name
    * lastname
    * address (opcional)
   
    
PUT http://icsseseguridad.com/location-security/public/clerk/{id} 

    Actualiza un funcionario, recibe parametros en el body:
    * dni
    * name
    * lastname
    * address (opcional)

DELETE http://icsseseguridad.com/location-security/public/clerk/{id}

    Elimina un funcionario por el id
    
# * Vehiculo Visitante (Visitor vehicle)

GET http://icsseseguridad.com/location-security/public/visitor-vehicle

    obtiene la lista de vehiculos visitantes
    
GET http://icsseseguridad.com/location-security/public/visitor-vehicle/active/1

    obtiene la lista de vehiculos visitantes activos

GET http://icsseseguridad.com/location-security/public/visitor-vehicle/{id}

    obtiene un vehiculo vitante por su id
    
GET http://icsseseguridad.com/location-security/public/visitor-vehicle/plate/{plate}

    obtiene un vehiculo vitante por la placa
    
POST http://icsseseguridad.com/location-security/public/visitor-vehicle/register 

    Registrar un vehiculo vitante, recibe parametros en el body:
    * plate
    * model
    * type
    * vehicle (opcional)
    * photo (opcional)
   
    
PUT http://icsseseguridad.com/location-security/public/visitor-vehicle/{id} 

    Actualiza un vehiculo vitante, recibe parametros en el body:
    * plate
    * model
    * type
    * vehicle (opcional)
    * photo (opcional)

DELETE http://icsseseguridad.com/location-security/public/visitor-vehicle/{id}

    Elimina un vehiculo vitante por el id
    
# * Visita (visit)

GET http://icsseseguridad.com/location-security/public/visit

    obtiene la lista de visitas
    
GET http://icsseseguridad.com/location-security/public/visit/active/1

    obtiene la lista de visitas activas

GET http://icsseseguridad.com/location-security/public/visit/{id}

    obtiene una visita por su id
    
GET http://icsseseguridad.com/location-security/public/visit/date/today
 
     obtiene todas las visitas registradas del dia actual
    
GET http://icsseseguridad.com/location-security/public/visit/date/{year}/{month}/{day}

    obtiene todas las visitas registradas del dia seleccionado
    
GET http://icsseseguridad.com/location-security/public/visit/guard/{id}     <- by Guard
 
     obtiene todas las visitas registradas de un empleado por el id
     
GET http://icsseseguridad.com/location-security/public/visit/guard/{id}/date
 
     obtiene todas las visitas registradas de un empleado por el id del dia actual
     
GET http://icsseseguridad.com/location-security/public/visit/guard/{id}/date/{year}/{month}/{day}
 
     obtiene todas las visitas registradas de un empleado por el id del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/visit/vehicle/{id}       <- by Vehicle    
 
     obtiene todas las visitas registradas por el id del vehiculo
     
GET http://icsseseguridad.com/location-security/public/visit/vehicle/{id}/date
 
     obtiene todas las visitas registradas por el id del vehiculo del dia actual
     
GET http://icsseseguridad.com/location-security/public/visit/vehicle/{id}/date/{year}/{month}/{day}
 
     obtiene todas las visitas registradas por el id del vehiculo del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/visit/visitor/{id}       <- by Visitor
 
     obtiene todas las visitas registradas por el id del visitante
     
GET http://icsseseguridad.com/location-security/public/visit/visitor/{id}/date
 
     obtiene todas las visitas registradas por el id del visitante del dia actual
     
GET http://icsseseguridad.com/location-security/public/visit/visitor/{id}/date/{year}/{month}/{day}
 
     obtiene todas las visitas registradas por el id del visitante del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/visit/clerk/{id}     <- by Clerk
 
     obtiene todas las visitas registradas por el id del funcionario visitado
     
GET http://icsseseguridad.com/location-security/public/visit/clerk/{id}/date
 
     obtiene todas las visitas registradas por el id del funcionario visitado del dia actual
     
GET http://icsseseguridad.com/location-security/public/visit/clerk/{id}/date/{year}/{month}/{day}
 
     obtiene todas las visitas registradas por el id del funcionario visitado del dia seleccionado
    
POST http://icsseseguridad.com/location-security/public/visit 

    Registrar una visita, recibe parametros en el body:
    * visitor_id
    * guard_id
    * vehicle_id (opcional)
    * visited_id (opcional)
    * persons
    * latitude
    * longitude
    * observation (opcional)
   
PUT http://icsseseguridad.com/location-security/public/visit/{id} 

    Finaliza la visita por el id

DELETE http://icsseseguridad.com/location-security/public/visit/{id}

    Elimina una visita por el id
    
# * Alerta (alert)

GET http://icsseseguridad.com/location-security/public/alert

    obtiene la lista de alertas
    
GET http://icsseseguridad.com/location-security/public/alert/active/1

    obtiene la lista de alertas activas

GET http://icsseseguridad.com/location-security/public/alert/{id}

    obtiene una alerta por su id
    
POST http://icsseseguridad.com/location-security/public/alert 

    Registrar una visita, recibe parametros en el body:
    * guard_id
    * cause
    * extra (opcional)
    * latitude
    * longitude
    
PUT http://icsseseguridad.com/location-security/public/alert/{id} 

    Pone la alerta como solventada

DELETE http://icsseseguridad.com/location-security/public/alert/{id}

    Elimina una alerta por el id
    
# * Configuracion (utility)

GET http://icsseseguridad.com/location-security/public/utility

    obtiene la lista de configuraciones
    

GET http://icsseseguridad.com/location-security/public/utility/{id}

    obtiene una configuracion por su id
    
GET http://icsseseguridad.com/location-security/public/utility/name/{name}

    obtiene una configuracion por su nombre
    
POST http://icsseseguridad.com/location-security/public/alert 

    Registrar una configuracion, recibe parametros en el body:
    * name
    * value
    
PUT http://icsseseguridad.com/location-security/public/utility/{id} 

    Edita el valor de una configuracion, recibe parametros en el body:
    * value 

DELETE http://icsseseguridad.com/location-security/public/utility/{id}

    Borra una configuracion
    
# * Position de las Tablets (tablet-position)

GET http://icsseseguridad.com/location-security/public/tablet

    Obtiene el ultimo registro de cada tablet

POST http://icsseseguridad.com/location-security/public/tablet

    Registrar la posicion, recibe parametros en el body:
    * latitude
    * longitude
    * watch_id
    * imei
    * message
    
GET http://icsseseguridad.com/location-security/public/tablet/all

    obtiene todas las posiciones registrar (usar solo para pruebas, no poner en produccion)
    
GET http://icsseseguridad.com/location-security/public/tablet/id/{id}
 
     obtiene una posicion por su id
    
GET http://icsseseguridad.com/location-security/public/tablet/date/today
 
     obtiene todas las posiciones registradas del dia actual
    
GET http://icsseseguridad.com/location-security/public/tablet/date/{year}/{month}/{day}
 
     obtiene todas las posiciones registradas del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/tablet/watch/{id}
 
     obtiene todas las posiciones registradas de una guardia por el id
     
GET http://icsseseguridad.com/location-security/public/tablet/watch/{id}/date
 
     obtiene todas las posiciones registradas de una guardia por el id del dia actual
     
GET http://icsseseguridad.com/location-security/public/tablet/watch/{id}/date/{year}/{month}/{day}
 
     obtiene todas las posiciones registradas de una guardia por el id del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/tablet/guard/{id}
 
     obtiene todas las posiciones registradas de un empleado por el id
     
GET http://icsseseguridad.com/location-security/public/tablet/guard/{id}/date
 
     obtiene todas las posiciones registradas de un empleado por el id del dia actual
     
GET http://icsseseguridad.com/location-security/public/tablet/guard/{id}/date/{year}/{month}/{day}
 
     obtiene todas las posiciones registradas de un empleado por el id del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/tablet/imei/{imei}
 
     obtiene todas las posiciones registradas de una tablet por su imei
     
GET http://icsseseguridad.com/location-security/public/tablet/imei/{imei}/date
 
     obtiene todas las posiciones registradas de una tablet por su imei del dia actual
     
GET http://icsseseguridad.com/location-security/public/tablet/imei/{imei}/date/{year}/{month}/{day}
 
     obtiene todas las posiciones registradas de una tablet por su imei del dia seleccionado
     
GET http://icsseseguridad.com/location-security/public/tablet/message/{message}
 
     obtiene todas las posiciones registradas con ese message
     
GET http://icsseseguridad.com/location-security/public/tablet/message/{message}/date
 
     obtiene todas las posiciones registradas con ese message del dia actual
     
GET http://icsseseguridad.com/location-security/public/tablet/message/{message}/date/{year}/{month}/{day}
 
     obtiene todas las posiciones registradas con ese message del dia seleccionado
     
# * Messenger (CHAT)

POST http://icsseseguridad.com/location-security/public/messenger/register/web

    Registrar el token de la pagina web, recibe parametros en el body:
    * registration_id   (token firebase)
    * admin_id
    * session  (identificador de session o ip, esta en veremos el uso) 
    
POST http://icsseseguridad.com/location-security/public/messenger/register/tablet

    Registrar el token de la los dispositivos, recibe parametros en el body:
    * registration_id   (token firebase)
    * guard_id
    * imei   
  
POST http://icsseseguridad.com/location-security/public/messenger/chat

    Creacion de chat con otro individuo, recibe parametros en el body:
    * user_1_id         (id del usuario)
    * user_1_type       (tipo de usuario/ ADMIN o GUARD)
    * user_1_name       (nombre completo del usuario)  
    * user_2_id         (id del usuario)
    * user_2_type       (tipo de usuario/ ADMIN o GUARD)
    * user_2_name       (nombre completo del usuario) 
 
POST http://icsseseguridad.com/location-security/public/messenger/send

    Envio de mensaje al otro usuario, recibe parametros en el body: 
    * text              (mensaje que se envia)
    * chat_id       
    * sender_id         (id del usuario que envia)
    * sender_type       (tipo de usuario que envia. ADMIN o GUARD)
    * sender_name       (nombre completo del que envia)
    
GET http://icsseseguridad.com/location-security/public/messenger/conversations/admin/{id}
 
     obtiene todas los chat abiertos de un administrador por su id
     
GET http://icsseseguridad.com/location-security/public/messenger/conversations/guard/{id}
 
     obtiene todas los chat abiertos de un guardia por su id
     
GET http://icsseseguridad.com/location-security/public/messenger/conversations/chat/{id}
 
     obtiene todos los mensajes de una conversacion atravez del id dle chat
     
# * Banner [imagenes para mostras en el home de las tablets]

GET http://icsseseguridad.com/location-security/public/banner

    obtiene la lista de banners

GET http://icsseseguridad.com/location-security/public/banner/{id}

    obtiene un banner por su id
   
POST http://icsseseguridad.com/location-security/public/banner 

    Registrar una banner, recibe parametro en el body:
    * photo (url)

DELETE http://icsseseguridad.com/location-security/public/banner/{id}

    Elimina una banner por el id
     
        