-- select * from ceva.geo_asistencia where rut = '258449843' order by date
-- select * from ceva.geo_trabajadores ;
-- select * from ceva.rep_geoasistencia order by atraso


-- select * from ceva.rep_geoasistencia where rut='97850402' order by rut,fecha
-- select * from geo_grupos
-- select * from ceva.geo_trabajadores where fecha='2024-02-05' and rut='171510325'

-- update rep_geoasistencia SET ATRASO = (CAST(SUBSTRING(ATRASO, 1, 2) AS UNSIGNED))*60+CAST(SUBSTRING(ATRASO, 4, 5) AS UNSIGNED)



-- 15:54:03	update rep_geoasistencia SET ATRASO = (CAST(SUBSTRING(ATRASO, 1, 2) AS UNSIGNED))*60+CAST(SUBSTRING(ATRASO, 4, 5) AS UNSIGNED)	Error Code: 1175. You are using safe update mode and you tried to update a table without a WHERE that uses a KEY column.  To disable safe mode, toggle the option in Preferences -> SQL Editor and reconnect.	0.00095 sec






