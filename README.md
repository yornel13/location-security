# REST API

* Url basico http://icsseseguridad.com/location-security/public/
* la api acepta consultas GET, POST y DELETE  segun sea el caso
* todas las respuesta son en formato JSON
* todas las consulta son POST el body va con formato JSON
* Usar Postman, Restlet Client, o alguna otra extension de chrome para probar las url
* Probar los get, post, delete para entender los resultados y trabajar con ellos
* En las url, lo que este entre {} es por lo que debe remplazar. Ejemplo:

"http://icsseseguridad.com/location-security/public/vehicle/get/{imei}"

se rempleaza por 

"http://icsseseguridad.com/location-security/public/vehicle/get/867162025555236"

los GET responden con la lista de objectos solicitados y el total

    http://icsseseguridad.com/location-security/public/incidence/get
    
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

    http://icsseseguridad.com/location-security/public/incidence/get/5
    
    {
        "id": "5",
        "name": "Otro"
    }

Si la solicitud get al objecto especifico no arroja ninguna resultado la respuesta sera un simple false
    
    http://icsseseguridad.com/location-security/public/incidence/get/58
    
    false
    
    
los POST y DELETE tienen la respuesta con el siguiente formato

    {
        "result": null,
        "response": true,
        "message": "",
        "errors": []
    }
    
Algunos POST devolveran datos en el 'result' segun sea necesario, por ejemplo el LOGIN que devuelve el token de session

    {
        "result": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1MzMzMjE2MzQsImF1ZCI6IjBkN2NiNjBiYmY1ZTQxYmYxMGFjM2FjMzZjOWM4NzAwZDQ2MjlhYmYiLCJkYXRhIjp7ImlkIjoiMSIsIk5vbWJyZSI6Illvcm5lbCIsIk5vbWJyZUNvbXBsZXRvIjoiWW9ybmVsIE1hcnZhbCIsIkVzQWRtaW4iOnRydWV9fQ.nTIUDX_7ZmvWiRHFZ7sWVCLlfvkh6dzqHluwrdqd394",
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
    * email
    * password
    
    
POST http://icsseseguridad.com/location-security/public/auth/guard

    logeo de guardias, recibe por body
    * dni
    * password
    
tanto en logeo de admin como de guardia la respuesta de ser exitosa sera: 

    {
        "result": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1MzMzMjE2MzQsImF1ZCI6IjBkN2NiNjBiYmY1ZTQxYmYxMGFjM2FjMzZjOWM4NzAwZDQ2MjlhYmYiLCJkYXRhIjp7ImlkIjoiMSIsIk5vbWJyZSI6Illvcm5lbCIsIk5vbWJyZUNvbXBsZXRvIjoiWW9ybmVsIE1hcnZhbCIsIkVzQWRtaW4iOnRydWV9fQ.nTIUDX_7ZmvWiRHFZ7sWVCLlfvkh6dzqHluwrdqd394",
        "response": true,
        "message": "",
        "errors": []
    }

y el 'result' sera en token de session


# * Vehiculos (Vehicles)
(Vehiculos proporcianados por claro-flatas)

GET http://icsseseguridad.com/location-security/public/vehicle/get

    Obtiene la lista de vehiculos con su ultima ubicacion

GET http://icsseseguridad.com/location-security/public/vehicle/get/{imei}

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

GET http://icsseseguridad.com/location-security/public/watch/get_active

    Obtiene todas las watches activas

GET http://icsseseguridad.com/location-security/public/watch/get

    Obtiene todas las guardias hechas (deberia tener un filtro por dia que aun no se ha hecho)

GET http://icsseseguridad.com/location-security/public/watch/get/{id}

    Obtiene una guardia por su id

POST http://icsseseguridad.com/location-security/public/watch/register

    Registrar (inicia) una guardia, recibe parametros en el body:
    * guard_id
    * latitude
    * longitude
    * observation (opcional)

POST http://icsseseguridad.com/location-security/public/watch/finish/{watch_id}

    Finaliza una guardia por el id, recibe parametros en el body:
    * latitude
    * longitude
    * observation (opcional)

# * Empleados (Guards)

GET http://icsseseguridad.com/location-security/public/guard/get

    obtiene la lista de empleados

GET http://icsseseguridad.com/location-security/public/guard/get/{id}

    obtiene un empleado por su id
    
GET http://icsseseguridad.com/location-security/public/guard/get/dni/{dni}

    obtiene un empleado por la cedula
    
POST http://icsseseguridad.com/location-security/public/guard/register 

    Registrar un empleado, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password
    
POST http://icsseseguridad.com/location-security/public/guard/update/{id} 

    Edita un empleado, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password (opcional)

DELETE http://icsseseguridad.com/location-security/public/guard/delete/{id}

    Elimina un empleado por el id
    
# * Administradores (admins)

GET http://icsseseguridad.com/location-security/public/admin/get

    obtiene la lista de administradores

GET http://icsseseguridad.com/location-security/public/admin/get/{id}

    obtiene un administrador por su id
    
GET http://icsseseguridad.com/location-security/public/admin/get/dni/{dni}

    obtiene un administrador por la cedula
    
POST http://icsseseguridad.com/location-security/public/admin/register 

    Registrar un administrador, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password
    
POST http://icsseseguridad.com/location-security/public/admin/update/{id} 

    Edita un administrador, recibe parametros en el body:
    * dni
    * name
    * lastname
    * email
    * password (opcional)

DELETE http://icsseseguridad.com/location-security/public/admin/delete/{id}

    Elimina un administrador por el id
    
# * Incidencias (incidences) [son los tipos de indicencias]

GET http://icsseseguridad.com/location-security/public/incidence/get

    obtiene la lista de incidencias

GET http://icsseseguridad.com/location-security/public/incidence/get/{id}

    obtiene una incidencia por su id
    
GET http://icsseseguridad.com/location-security/public/incidence/get/name/{name}

    obtiene una incidencia por su nombre
    
POST http://icsseseguridad.com/location-security/public/incidence/register 

    Registrar una incidencia, recibe parametros en el body:
    * name
    
POST http://icsseseguridad.com/location-security/public/incidence/update/{id} 

    Edita una incidencia, recibe parametros en el body:
    * name

DELETE http://icsseseguridad.com/location-security/public/incidence/delete/{id}

    Elimina una incidencia por el id
    
# * Reporte Especial (special_report)

* El estatus 1 significa que aun no se ha aceptado la notificacion 
* El estatus 2 significa que ya se ha aceptado la notificacion

GET http://icsseseguridad.com/location-security/public/special/report/get

    obtiene la lista de reportes
    
GET http://icsseseguridad.com/location-security/public/special/report/get_active

    obtiene la lista de reportes a los cuales no se le ha aceptado la notificacion

GET http://icsseseguridad.com/location-security/public/special/report/get/{id}

    obtiene un reporte por su id
    
GET http://icsseseguridad.com/location-security/public/special/report/get/{id}/replies

    obtiene la lista de comentarios hechos a dicho reporte
    
POST http://icsseseguridad.com/location-security/public/special/report/register 

    Registrar un reporte, recibe parametros en el body:
    * incidence_id
    * watch_id
    * title
    * observation
    * latitude
    * longitude
    
POST http://icsseseguridad.com/location-security/public/special/report/update/{id} 

    Pasa el reporte al estado 2, que quiere decir que ya se acepto la notificacion, 
    no recibe parametros.
  
DELETE http://icsseseguridad.com/location-security/public/special/report/delete/{id}

    Elimina el reporte por el id
    
# * Comentarios al reporte especial (replies)

GET http://icsseseguridad.com/location-security/public/special/report/reply/get

    obtiene la lista de comentarios

GET http://icsseseguridad.com/location-security/public/special/report/reply/get/{id}

    obtiene un comentario por su id
    
POST http://icsseseguridad.com/location-security/public/special/report/reply/register 

    Registrar un comentario, recibe parametros en el body:
    enviar admin_id si comenta un administrador, enviar guard_id si comenta un empleado
    * report_id
    * text
    * admin_id (obligatorio si no tiene guard_id) 
    * guard_id (obligatorio si no tiene admin_id) 
    

DELETE http://icsseseguridad.com/location-security/public/special/report/reply/delete/{id}

    Elimina un comentario por el id
    
# * Visitantes (Visitors)

GET http://icsseseguridad.com/location-security/public/visitor/get

    obtiene la lista de visitante

GET http://icsseseguridad.com/location-security/public/visitor/get/{id}

    obtiene un visitante por su id
    
GET http://icsseseguridad.com/location-security/public/visitor/get/dni/{dni}

    obtiene un visitante por la cedula
    
POST http://icsseseguridad.com/location-security/public/visitor/register 

    Registrar un visitante, recibe parametros en el body:
    * dni
    * name
    * lastname
    * company (opcional)
    * photo (opcional)(url)
   
    
POST http://icsseseguridad.com/location-security/public/visitor/update/{id} 

    Actualiza un visitante, recibe parametros en el body:
    * dni
    * name
    * lastname
    * company (opcional)
    * photo (opcional)(url)

DELETE http://icsseseguridad.com/location-security/public/visitor/delete/{id}

    Elimina un visitante por el id
    
# * Funcionario (Clerks)

GET http://icsseseguridad.com/location-security/public/clerk/get

    obtiene la lista de funcionarios

GET http://icsseseguridad.com/location-security/public/clerk/get/{id}

    obtiene un funcionario por su id
    
GET http://icsseseguridad.com/location-security/public/clerk/get/dni/{dni}

    obtiene un funcionario por la cedula
    
POST http://icsseseguridad.com/location-security/public/clerk/register 

    Registrar un funcionario, recibe parametros en el body:
    * dni
    * name
    * lastname
    * address (opcional)
   
    
POST http://icsseseguridad.com/location-security/public/clerk/update/{id} 

    Actualiza un funcionario, recibe parametros en el body:
    * dni
    * name
    * lastname
    * address (opcional)

DELETE http://icsseseguridad.com/location-security/public/clerk/delete/{id}

    Elimina un funcionario por el id
    
# * Vehiculo Visitante (Visitor vehicle)

GET http://icsseseguridad.com/location-security/public/visitor/vehicle/get

    obtiene la lista de vehiculos visitantes

GET http://icsseseguridad.com/location-security/public/visitor/vehicle/get/{id}

    obtiene un vehiculo vitante por su id
    
GET http://icsseseguridad.com/location-security/public/visitor/vehicle/get/plate/{plate}

    obtiene un vehiculo vitante por la placa
    
POST http://icsseseguridad.com/location-security/public/visitor/vehicle/register 

    Registrar un vehiculo vitante, recibe parametros en el body:
    * plate
    * model
    * type
    * vehicle (opcional)
    * photo (opcional)
   
    
POST http://icsseseguridad.com/location-security/public/visitor/vehicle/update/{id} 

    Actualiza un vehiculo vitante, recibe parametros en el body:
    * plate
    * model
    * type
    * vehicle (opcional)
    * photo (opcional)

DELETE http://icsseseguridad.com/location-security/public/visitor/vehicle/delete/{id}

    Elimina un vehiculo vitante por el id
    
# * Visita (visit)

GET http://icsseseguridad.com/location-security/public/visit/get

    obtiene la lista de visitas
    
GET http://icsseseguridad.com/location-security/public/visit/get_active

    obtiene la lista de visitas activas

GET http://icsseseguridad.com/location-security/public/visit/get/{id}

    obtiene una visita por su id
    
POST http://icsseseguridad.com/location-security/public/visit/register 

    Registrar una visita, recibe parametros en el body:
    * visitor_id
    * guard_id
    * vehicle_id (opcional)
    * visited_id (opcional)
    * persons
    * latitude
    * longitude
    * observation (opcional)
   
    
POST http://icsseseguridad.com/location-security/public/visit/finish/{id} 

    Finaliza la visita por el id

DELETE http://icsseseguridad.com/location-security/public/visit/delete/{id}

    Elimina una visita por el id
    
# * Alerta (alert)

GET http://icsseseguridad.com/location-security/public/alert/get

    obtiene la lista de alertas
    
GET http://icsseseguridad.com/location-security/public/alert/get_active

    obtiene la lista de alertas activas

GET http://icsseseguridad.com/location-security/public/alert/get/{id}

    obtiene una alerta por su id
    
POST http://icsseseguridad.com/location-security/public/alert/register 

    Registrar una visita, recibe parametros en el body:
    * guard_id
    * cause
    * extra (opcional)
    * latitude
    * longitude
    
POST http://icsseseguridad.com/location-security/public/alert/update/{id} 

    Pone la alerta como solventada

DELETE http://icsseseguridad.com/location-security/public/alert/delete/{id}

    Elimina una alerta por el id