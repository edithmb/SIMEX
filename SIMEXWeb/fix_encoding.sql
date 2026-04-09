-- =============================================
-- FIX ENCODING: Corregir datos con encoding roto
-- Base de datos: simex06
-- =============================================

-- COUNTRIES (6 registros afectados)
UPDATE countries SET name = N'España' WHERE id = 1;
UPDATE countries SET name = N'México' WHERE id = 8;
UPDATE countries SET name = N'Japón' WHERE id = 11;
UPDATE countries SET name = N'Canadá' WHERE id = 13;

-- CITIES (16 registros afectados)
UPDATE cities SET name = N'París' WHERE id = 3;
UPDATE cities SET name = N'Milán' WHERE id = 6;
UPDATE cities SET name = N'Berlín' WHERE id = 7;
UPDATE cities SET name = N'Múnich' WHERE id = 8;
UPDATE cities SET name = N'Los Ángeles' WHERE id = 14;
UPDATE cities SET name = N'México' WHERE id = 15;
UPDATE cities SET name = N'Córdoba' WHERE id = 18;
UPDATE cities SET name = N'São Paulo' WHERE id = 19;
UPDATE cities SET name = N'Río de Janeiro' WHERE id = 20;
UPDATE cities SET name = N'Pekín' WHERE id = 23;
UPDATE cities SET name = N'Shanghái' WHERE id = 24;
UPDATE cities SET name = N'Sídney' WHERE id = 27;

-- AIRPORTS (18 registros afectados)
UPDATE airports SET name = N'Aeropuerto Adolfo Suárez Madrid-Barajas' WHERE id = 1;
UPDATE airports SET name = N'Aeropuerto de París-Charles de Gaulle' WHERE id = 3;
UPDATE airports SET name = N'Aeropuerto de Milán-Malpensa' WHERE id = 6;
UPDATE airports SET name = N'Aeropuerto de Berlín Brandeburgo' WHERE id = 7;
UPDATE airports SET name = N'Aeropuerto de Múnich' WHERE id = 8;
UPDATE airports SET name = N'Aeropuerto Francisco Sá Carneiro (Oporto)' WHERE id = 10;
UPDATE airports SET name = N'Aeropuerto Internacional de Los Ángeles' WHERE id = 14;
UPDATE airports SET name = N'Aeropuerto Internacional de la Ciudad de México' WHERE id = 15;
UPDATE airports SET name = N'Aeropuerto Internacional de São Paulo-Guarulhos' WHERE id = 19;
UPDATE airports SET name = N'Aeropuerto Internacional de Río de Janeiro-Galeão' WHERE id = 20;
UPDATE airports SET name = N'Aeropuerto Internacional de Pekín-Capital' WHERE id = 23;
UPDATE airports SET name = N'Aeropuerto Internacional de Shanghái-Pudong' WHERE id = 24;
UPDATE airports SET name = N'Aeropuerto de Sídney' WHERE id = 27;

-- PORTS (12 registros afectados)
UPDATE ports SET name = N'Puerto de París' WHERE id = 3;
UPDATE ports SET name = N'Puerto de Milán' WHERE id = 6;
UPDATE ports SET name = N'Puerto de Berlín' WHERE id = 7;
UPDATE ports SET name = N'Puerto de Múnich' WHERE id = 8;
UPDATE ports SET name = N'Puerto de Los Ángeles' WHERE id = 14;
UPDATE ports SET name = N'Puerto de Ciudad de México' WHERE id = 15;
UPDATE ports SET name = N'Puerto de Córdoba' WHERE id = 18;
UPDATE ports SET name = N'Puerto de São Paulo' WHERE id = 19;
UPDATE ports SET name = N'Puerto de Río de Janeiro' WHERE id = 20;
UPDATE ports SET name = N'Puerto de Pekín' WHERE id = 23;
UPDATE ports SET name = N'Puerto de Shanghái' WHERE id = 24;
UPDATE ports SET name = N'Puerto de Sídney' WHERE id = 27;

-- CLIENTS (1 registro afectado)
UPDATE clients SET company_name = N'Logística Edith' WHERE id = 3;
UPDATE clients SET country = N'España' WHERE id = 3;

-- CONTAINER_TYPES (1 registro afectado)
UPDATE container_types SET type_name = N'Consolidació Pròpia' WHERE id = 4;

-- DOCUMENT_TYPES - descripciones (6 registros afectados)
UPDATE document_types SET description = N'Factura comercial que detalla el valor y descripción de la mercancía' WHERE id = 1;
UPDATE document_types SET description = N'DUA de Exportación o documento de despacho aduanero de salida' WHERE id = 3;
UPDATE document_types SET description = N'Certificado o póliza de seguro de transporte' WHERE id = 5;
UPDATE document_types SET description = N'Albarán, recibo del transportista o FCR (Forwarders Certificate of Receipt)' WHERE id = 6;
UPDATE document_types SET description = N'DUA de Importación o documento de despacho aduanero de entrada' WHERE id = 7;
