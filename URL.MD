# REST API

* Url basico https://icsseseguridad.com/api/public/


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

GET https://icsseseguridad.com/api/public/watch/active/1

    Obtiene todas las watches activas

GET https://icsseseguridad.com/api/public/watch

    Obtiene todas las guardias hechas

GET https://icsseseguridad.com/api/public/watch/{id}

    Obtiene una guardia por su id
    
GET https://icsseseguridad.com/api/public/watch/date/today

    Obtiene todas las guardias del dia actual

GET https://icsseseguridad.com/api/public/watch/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    Obtiene todas las guardias del dia seleccionado
    
GET https://icsseseguridad.com/api/public/watch/guard/{id}

    Obtiene todas las guardias por el id del guardia
    
GET https://icsseseguridad.com/api/public/watch/guard/{id}/active/1

    Obtiene todas las guardias activas por el id del guardia

GET https://icsseseguridad.com/api/public/watch/guard/{id}/date

    Obtiene todas las guardias por el id del guardia del dia actual

GET https://icsseseguridad.com/api/public/watch/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    Obtiene todas las guardias por el id del guardia del dia seleccionado
    
GET https://icsseseguridad.com/api/public/watch/{id}/history

    Obtiene todas los registro de una guardia

# * Empleados (Guards)

GET https://icsseseguridad.com/api/public/guard

    obtiene la lista de empleados
    
GET https://icsseseguridad.com/api/public/guard/active/1

    obtiene la lista de empleados activos
    
GET https://icsseseguridad.com/api/public/guard/active/1

    obtiene la lista de empleados desactivados

GET https://icsseseguridad.com/api/public/guard/{id}

    obtiene un empleado por su id
    
GET https://icsseseguridad.com/api/public/guard/dni/{dni}

    obtiene un empleado por la cedula
    
# * Administradores (admins)

GET https://icsseseguridad.com/api/public/admin

    obtiene la lista de administradores
    
GET https://icsseseguridad.com/api/public/admin/active/1

    obtiene la lista de administradores activos

GET https://icsseseguridad.com/api/public/admin/{id}

    obtiene un administrador por su id
    
GET https://icsseseguridad.com/api/public/admin/dni/{dni}

    obtiene un administrador por la cedula
    
# * Incidencias (incidences) [son los tipos de indicencias]

GET https://icsseseguridad.com/api/public/incidence

    obtiene la lista de incidencias

GET https://icsseseguridad.com/api/public/incidence/{id}

    obtiene una incidencia por su id
    
GET https://icsseseguridad.com/api/public/incidence/name/{name}

    obtiene una incidencia por su nombre
    
# * Reporte Especial (special_report)

    * El estatus 1 significa que aun no se ha aceptado la notificacion 
    * El estatus 2 significa que ya se ha aceptado la notificacion
    
    todas las consultas requieren especificar el resolved
    
    Hay 5 especificaciones posibles para el resolved:
    
        resolved = all  -> todos 
        resolved = open -> abierto y reabiertos (1,2)
        resolved = 0    -> cerrados 
        resolved = 1    -> abiertos 
        resolved = 2    -> reabiertos 

GET https://icsseseguridad.com/api/public/binnacle

    obtiene la lista de reportes
    
GET https://icsseseguridad.com/api/public/binnacle/{id}

    obtiene un reporte por su id
    
GET https://icsseseguridad.com/api/public/binnacle/active/1

    obtiene la lista de reportes de bitacora a los cuales no se le ha aceptado la notificacion
    
    
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}

    obtiene la lista de reportes del dia actual
    
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date

    obtiene la lista de reportes del dia actual
    
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de reportes del dia seleccionado
    
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/incidence/{id}     <- by Incidence
 
     obtiene todas los reportes registrados de un tipo incidence por el id
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/incidence/{id}/date
 
     obtiene todas los reportes registrados de un tipo incidence por el id del dia actual
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/incidence/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas los reportes registrados de un tipo incidence por el id del dia seleccionado
         
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/guard/{id}     <- by Guard
 
     obtiene todas los reportes registrados de un empleado por el id
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/guard/{id}/date
 
     obtiene todas las visitas registrados de un empleado por el id del dia actual
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registrados de un empleado por el id del dia seleccionado
    
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/watch/{id}     <- by Watch
 
     obtiene todas los reportes registrados en una guardia por el id
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/watch/{id}/date
 
     obtiene todas las visitas registrados en una guardia por el id del dia actual
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/watch/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registrados en una guardia por el id del dia seleccionado
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}     <- by resolved
 
     obtiene todas los reportes registrados en estado de su resolucion
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date
 
     obtiene todas los reportes registrados en estado de su resolucion del dia actual
     
GET https://icsseseguridad.com/api/public/binnacle/resolved/{resolved}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas los reportes registrados en estado de su resolucion del dia seleccionado
    
GET https://icsseseguridad.com/api/public/binnacle/{id}/replies

    obtiene la lista de comentarios hechos a un reporte
    
# * Comentarios al reporte especial (replies)

GET https://icsseseguridad.com/api/public/binnacle-reply

    obtiene la lista de comentarios

GET https://icsseseguridad.com/api/public//binnacle-reply/{id}

    obtiene un comentario por su id
    
# * Visitantes (Visitors)

GET https://icsseseguridad.com/api/public/visitor

    obtiene la lista de visitantes
    
GET https://icsseseguridad.com/api/public/visitor/active/1

    obtiene la lista de visitantes activos

GET https://icsseseguridad.com/api/public/visitor/{id}

    obtiene un visitante por su id
    
GET https://icsseseguridad.com/api/public/visitor/dni/{dni}

    obtiene un visitante por la cedula
    
# * Funcionario (Clerks)

GET https://icsseseguridad.com/api/public/clerk

    obtiene la lista de funcionarios
    
GET https://icsseseguridad.com/api/public/clerk/active/1

    obtiene la lista de funcionarios activos

GET https://icsseseguridad.com/api/public/clerk/{id}

    obtiene un funcionario por su id
    
GET https://icsseseguridad.com/api/public/clerk/dni/{dni}

    obtiene un funcionario por la cedula
    
# * Vehiculo Visitante (Visitor vehicle)

GET https://icsseseguridad.com/api/public/visitor-vehicle

    obtiene la lista de vehiculos visitantes
    
GET https://icsseseguridad.com/api/public/visitor-vehicle/active/1

    obtiene la lista de vehiculos visitantes activos

GET https://icsseseguridad.com/api/public/visitor-vehicle/{id}

    obtiene un vehiculo vitante por su id
    
GET https://icsseseguridad.com/api/public/visitor-vehicle/plate/{plate}

    obtiene un vehiculo vitante por la placa
    
# * Visita (visit)
    
    las visitas requieren el status en la url
        status = 1     -> visitas activas
        status = 0     -> visitas finalizadas
        status = all   -> todos los status
    
GET https://icsseseguridad.com/api/public/visit/active/1

    obtiene la lista de visitas activas

GET https://icsseseguridad.com/api/public/visit/{id}

    obtiene una visita por su id
    
GET https://icsseseguridad.com/api/public/visit/status/{status}

    obtiene la lista de visitas
    
GET https://icsseseguridad.com/api/public/visit/status/{status}/date
 
     obtiene todas las visitas registradas del dia actual
    
GET https://icsseseguridad.com/api/public/visit/status/{status}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene todas las visitas registradas del rango seleccionado
    
GET https://icsseseguridad.com/api/public/visit/status/{status}/guard/{id}     <- by Guard
 
     obtiene todas las visitas registradas de un empleado por el id
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/guard/{id}/date
 
     obtiene todas las visitas registradas de un empleado por el id del dia actual
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas de un empleado por el id del dia seleccionado
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/vehicle/{id}       <- by Vehicle    
 
     obtiene todas las visitas registradas por el id del vehiculo
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/vehicle/{id}/date
 
     obtiene todas las visitas registradas por el id del vehiculo del dia actual
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/vehicle/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas por el id del vehiculo del dia seleccionado
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/visitor/{id}       <- by Visitor
 
     obtiene todas las visitas registradas por el id del visitante
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/visitor/{id}/date
 
     obtiene todas las visitas registradas por el id del visitante del dia actual
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/visitor/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas por el id del visitante del dia seleccionado
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/clerk/{id}     <- by Clerk
 
     obtiene todas las visitas registradas por el id del funcionario visitado
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/clerk/{id}/date
 
     obtiene todas las visitas registradas por el id del funcionario visitado del dia actual
     
GET https://icsseseguridad.com/api/public/visit/status/{status}/clerk/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las visitas registradas por el id del funcionario visitado del dia seleccionado
    
# * Alerta (alert)

GET https://icsseseguridad.com/api/public/alert

    obtiene la lista de alertas
    
GET https://icsseseguridad.com/api/public/alert/active/1

    obtiene la lista de alertas activas

GET https://icsseseguridad.com/api/public/alert/{id}

    obtiene una alerta por su id
    
GET https://icsseseguridad.com/api/public/alert/cause/{cause}

    obtiene la lista de alertas por su causa
    
GET https://icsseseguridad.com/api/public/alert/cause/{cause}/date

    obtiene la lista de alertas por su causa del dia actual
    
GET https://icsseseguridad.com/api/public/alert/cause/{cause}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de alertas por su causa del dia seleccionado
    
GET https://icsseseguridad.com/api/public/alert/cause/{cause}/guard/{id}

    obtiene la lista de alertas por su causa y id del guardia
    
GET https://icsseseguridad.com/api/public/alert/cause/{cause}/guard/{id}/date

    obtiene la lista de alertas por su causa y id del guardia del dia actual
    
GET https://icsseseguridad.com/api/public/alert/cause/{cause}/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de alertas por su causa y id del guardia del dia seleccionado
    
# * Configuracion (utility)

GET https://icsseseguridad.com/api/public/utility

    obtiene la lista de configuraciones
    

GET https://icsseseguridad.com/api/public/utility/{id}

    obtiene una configuracion por su id
    
GET https://icsseseguridad.com/api/public/utility/name/{name}

    obtiene una configuracion por su nombre
     
# * Messenger (CHAT)

GET https://icsseseguridad.com/api/public/messenger/conversations/admin/{id}
 
     obtiene todas los chat abiertos de un administrador por su id
     
GET https://icsseseguridad.com/api/public/messenger/conversations/guard/{id}
 
     obtiene todas los chat abiertos de un guardia por su id
     
GET https://icsseseguridad.com/api/public/messenger/conversations/chat/{id}
 
     obtiene todos los mensajes de una conversacion atravez del id del chat
     
GET https://icsseseguridad.com/api/public/messenger/channel/guard/{id}
 
     obtiene todos los channel al que esta suscrito el guardia
     
GET https://icsseseguridad.com/api/public/messenger/channel/admin/{id}
 
     obtiene todos los channel al que esta suscrito el administrador
     
GET https://icsseseguridad.com/api/public/messenger/channel/{id}/members
 
     obtiene todos los miembros de un channel
     
GET https://icsseseguridad.com/api/public/messenger/conversations/channel/{id}
 
     obtiene todos los mensajes de un channel
 
# * Bounds 

    Los vehiculos solo pueden estar en un cerco a la vez

GET https://icsseseguridad.com/api/public/bounds

    obtiene todas los cercos virutales
    
GET https://icsseseguridad.com/api/public/bounds/{id}/vehicle

    obtiene todas los vehiculos asociados a un cerco virtual
    
GET https://icsseseguridad.com/api/public/bounds/{id}/tablet

    obtiene todas las tablet asociados a un cerco virtual
    
# * Bounds Groups (Grupos para cercos virtuales)
    
GET https://icsseseguridad.com/api/public/bounds_group

    obtiene todos los grupos

             
GET https://icsseseguridad.com/api/public/bounds/group/{bounds_group_id}

    obtiene todas los cerco por el grupo 
    
# * Tablets
    
GET https://icsseseguridad.com/api/public/tablet/active/{status}

    obtiene la lista de tablets por el estado, 
    all => Todas
    1   => Activas
    0   => Desactivadas
   
# * Stands
    
GET https://icsseseguridad.com/api/public/stand

    obtiene todos los puestos
   
GET https://icsseseguridad.com/api/public/tablet/stand/{stand_id}

    obtiene todas las tablets asociadas a un puesto
    
GET https://icsseseguridad.com/api/public/guard/stand/{stand_id}
    
    obtiene todas los guardias asociadas a un puesto
    
    
# * Position de las Tablets (tablet-position)

GET https://icsseseguridad.com/api/public/tablet

    Obtiene el ultimo registro de cada tablet
    
GET https://icsseseguridad.com/api/public/tablet/all

    obtiene todas las posiciones registrar (usar solo para pruebas, no poner en produccion)
    
GET https://icsseseguridad.com/api/public/tablet/id/{id}
 
     obtiene una posicion por su id
    
GET https://icsseseguridad.com/api/public/tablet/date/today
 
     obtiene todas las posiciones registradas del dia actual
    
GET https://icsseseguridad.com/api/public/tablet/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas del periodo seleccionado
     
GET https://icsseseguridad.com/api/public/tablet/watch/{id}
 
     obtiene todas las posiciones registradas de una guardia por el id
     
GET https://icsseseguridad.com/api/public/tablet/watch/{id}/date
 
     obtiene todas las posiciones registradas de una guardia por el id del dia actual
     
GET https://icsseseguridad.com/api/public/tablet/watch/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas de una guardia por el id del periodo seleccionado
     
GET https://icsseseguridad.com/api/public/tablet/guard/{id}
 
     obtiene todas las posiciones registradas de un empleado por el id
     
GET https://icsseseguridad.com/api/public/tablet/guard/{id}/date
 
     obtiene todas las posiciones registradas de un empleado por el id del dia actual
     
GET https://icsseseguridad.com/api/public/tablet/guard/{id}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas de un empleado por el id del periodo seleccionado
     
GET https://icsseseguridad.com/api/public/tablet/imei/{imei}
 
     obtiene todas las posiciones registradas de una tablet por su imei
     
GET https://icsseseguridad.com/api/public/tablet/imei/{imei}/date
 
     obtiene todas las posiciones registradas de una tablet por su imei del dia actual
     
GET https://icsseseguridad.com/api/public/tablet/imei/{imei}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
     obtiene todas las posiciones registradas de una tablet por su imei del periodo seleccionado
     
GET https://icsseseguridad.com/api/public/tablet/message/{message}
 
     obtiene todas las posiciones registradas con ese message
     
GET https://icsseseguridad.com/api/public/tablet/message/{message}/date
 
     obtiene todas las posiciones registradas con ese message del dia actual
     
GET https://icsseseguridad.com/api/public/tablet/message/{message}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}
 
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

GET https://icsseseguridad.com/api/public/alert/out/bounds

    obtiene la lista de salidas del cerco
    
GET https://icsseseguridad.com/api/public/alert/out/bounds/date

    obtiene la lista de salidas del cerco del dia actual
    
GET https://icsseseguridad.com/api/public/alert/out/bounds/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de salidas del cerco del rango seleccionado
    
GET https://icsseseguridad.com/api/public/alert/out/bounds/imei/{imei}

    obtiene la lista de salidas del cerco de un vehiculos a tra vez de su imei
    
GET https://icsseseguridad.com/api/public/alert/out/bounds/imei/{imei}/date

    obtiene la lista de salidas del cerco del dia actual de un vehiculos a tra vez de su imei
    
GET https://icsseseguridad.com/api/public/alert/out/bounds/imei/{imei}/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}

    obtiene la lista de salidas del cerco del rango seleccionado de un vehiculos a tra vez de su imei


