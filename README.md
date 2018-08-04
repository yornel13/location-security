# Api REST

* Url http://icsseseguridad.com/location-security/public/
* la api acepta consulta GET, POST y DELETE  segun sea el caso
* todas las respuesta son en formato JSON
* todas las consulta son POST el body va con formato JSON
* Los get si son a lista responde
* En las url, lo que este entre {} es por lo que debe remplazar. Ejemplo:

"http://icsseseguridad.com/location-security/public/vehicle/get/{imei}"

se rempleaza por 

"http://icsseseguridad.com/location-security/public/vehicle/get/867162025555236"


# Vehiculos (Vehicles)
(Vehiculos proporcianados por claro-flatas)

GET http://icsseseguridad.com/location-security/public/vehicle/get

    Obtiene la lista de vehiculos con su ultima ubicacion

GET http://icsseseguridad.com/location-security/public/vehicle/get/{imei}

    Obtiene el vehiculo por el imei

GET http://icsseseguridad.com/location-security/public/vehicle/history/{imei}

    Obtiene el historial del vehiculo con el imei del dia actual

GET http://icsseseguridad.com/location-security/public/vehicle/history/{imei}/{year}/{month}/{day}

    Obtiene el historial del vehiculo con el imei del dia seleccionado


# Guardias (Watches)

GET http://icsseseguridad.com/location-security/public/watch/get_active

    Obtiene todas las watches activas

GET http://icsseseguridad.com/location-security/public/watch/get

    Obtiene todas las guardias hechas (deberia tener un filtro por dia que aun no se ha hecho)

GET http://icsseseguridad.com/location-security/public/watch/get/{id}

    Obtiene una guardia por su id

POST http://icsseseguridad.com/location-security/public/watch/register

    Registrar una guardia, recibe parametros en el body:
    * guard_id
    * latitude
    * longitude
    * observation (opcional)

POST http://icsseseguridad.com/location-security/public/watch/finish/{watch_id}

    Finaliza una guardia por el id, recibe parametros en el body:
    * latitude
    * longitude
    * observation (opcional)

# Empleados (Guards)

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
    
