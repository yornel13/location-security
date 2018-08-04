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
    
    