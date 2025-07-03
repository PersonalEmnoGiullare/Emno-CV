-- Tabla: Clientes
CREATE TABLE Clientes (
    IdCliente INT PRIMARY KEY NOT NULL,
    RazonSocial VARCHAR(50) NOT NULL,
    TelCliente VARCHAR(25) NOT NULL,
    Domicilio VARCHAR(50) NOT NULL,
    RFC VARCHAR(13)
);
-- Tabla: Proyectos
CREATE TABLE Proyectos (
    IdProyecto INT PRIMARY KEY NOT NULL,
    Descripcion VARCHAR(50) NOT NULL,
    FechaInicio DATE NOT NULL,
    FechaFin DATE NOT NULL,
    IdCliente INT NOT NULL,
    Estado VARCHAR(50),
    FOREIGN KEY (IdCliente) REFERENCES Clientes(IdCliente)
);
-- Tabla: Colaboradores
CREATE TABLE Colaboradores (
    IdColaborador INT PRIMARY KEY NOT NULL,
    NombreColaborador VARCHAR(50) NOT NULL,
    TelColaborador VARCHAR(50) NOT NULL,
    NumCuenta VARCHAR(50) NOT NULL,
    Domicilio VARCHAR(50) NOT NULL,
    Email VARCHAR(50)
);
-- Tabla: ProyectoColaborador (Tabla puente para relación N a N)
CREATE TABLE ProyectoColaborador (
    IdProyectoColaborador INT PRIMARY KEY NOT NULL,
    IdProyecto INT NOT NULL,
    IdColaborador INT NOT NULL,
    FOREIGN KEY (IdProyecto) REFERENCES Proyectos(IdProyecto),
    FOREIGN KEY (IdColaborador) REFERENCES Colaboradores(IdColaborador)
);
-- Tabla: TiposDePago
CREATE TABLE TiposDePago (
    IdTipoPago INT PRIMARY KEY NOT NULL,
    Detalle VARCHAR(50) NOT NULL
);
-- Tabla: Pagos
CREATE TABLE Pagos (
    IdPago INT PRIMARY KEY NOT NULL,
    Concepto VARCHAR(50) NOT NULL,
    Cantidad FLOAT NOT NULL,
    Fecha DATE NOT NULL,
    IdColaborador INT NOT NULL,
    IdTipoPago INT NOT NULL,
    FOREIGN KEY (IdColaborador) REFERENCES Colaboradores(IdColaborador),
    FOREIGN KEY (IdTipoPago) REFERENCES TiposDePago(IdTipoPago)
);
--INSERTAR DATOS
INSERT INTO Clientes
VALUES (
        1,
        'Tecnología Global SA',
        '5551234567',
        'Av. Reforma 100, CDMX',
        NULL
    );
INSERT INTO Clientes
VALUES (
        2,
        'InnovaTech Solutions',
        '5552345678',
        'Calle 20 #45, Guadalajara',
        NULL
    );
INSERT INTO Clientes
VALUES (
        3,
        'Softwares Avanzados',
        '5553456789',
        'Blvd. Europa 300, Monterrey',
        NULL
    );
INSERT INTO Clientes
VALUES (
        4,
        'Desarrollos Mx',
        '5554567890',
        'Av. Universidad 123, Puebla',
        NULL
    );
INSERT INTO Clientes
VALUES (
        5,
        'Visionarios Digitales',
        '5555678901',
        'Calle 10 Sur, Mérida',
        NULL
    );
UPDATE Clientes
SET Domicilio = 'Talavera 232 Guadalupe',
    RazonSocial = 'hola'
WHERE IdCliente = 3;
INSERT INTO Proyectos
VALUES (
        1,
        'Sistema de gestión escolar',
        '2025-01-15',
        '2025-04-15',
        1,
        NULL
    );
INSERT INTO Proyectos
VALUES (
        2,
        'Plataforma de e-commerce',
        '2025-02-01',
        '2025-06-01',
        2,
        NULL
    );
INSERT INTO Proyectos
VALUES (
        3,
        'App de inventarios',
        '2025-03-10',
        '2025-05-30',
        1,
        NULL
    );
INSERT INTO Proyectos
VALUES (
        4,
        'Sistema contable',
        '2025-01-20',
        '2025-05-20',
        3,
        NULL
    );
INSERT INTO Proyectos
VALUES (
        5,
        'Portal de clientes',
        '2025-04-01',
        '2025-07-01',
        4,
        NULL
    );
INSERT INTO Colaboradores
VALUES (
        1,
        'Laura Gómez',
        '5551112233',
        '1234567890',
        'Av. Central 123',
        NULL
    );
INSERT INTO Colaboradores
VALUES (
        2,
        'Juan Pérez',
        '5552223344',
        '2345678901',
        'Calle Norte 45',
        NULL
    );
INSERT INTO Colaboradores
VALUES (
        3,
        'Ana Ruiz',
        '5553334455',
        '3456789012',
        'Col. Jardines 67',
        NULL
    );
INSERT INTO Colaboradores
VALUES (
        4,
        'Luis Ramírez',
        '5554445566',
        '4567890123',
        'Calle 5 de Mayo',
        NULL
    );
INSERT INTO Colaboradores
VALUES (
        5,
        'Marta Sánchez',
        '5555556677',
        '5678901234',
        'Av. Las Torres',
        NULL
    );
INSERT INTO ProyectoColaborador
VALUES (1, 1, 1);
INSERT INTO ProyectoColaborador
VALUES (2, 1, 2);
INSERT INTO ProyectoColaborador
VALUES (3, 2, 3);
INSERT INTO ProyectoColaborador
VALUES (4, 3, 1);
INSERT INTO ProyectoColaborador
VALUES (5, 4, 4);
INSERT INTO TiposDePago
VALUES (1, 'Transferencia');
INSERT INTO TiposDePago
VALUES (2, 'Depósito');
INSERT INTO TiposDePago
VALUES (3, 'Efectivo');
INSERT INTO TiposDePago
VALUES (4, 'Cheque');
INSERT INTO TiposDePago
VALUES (5, 'Pago en línea');
INSERT INTO Pagos
VALUES (
        1,
        'Pago mensual proyecto 1',
        15000.00,
        '2025-02-01',
        1,
        1
    );
INSERT INTO Pagos
VALUES (
        2,
        'Anticipo proyecto 2',
        8000.00,
        '2025-03-01',
        3,
        2
    );
INSERT INTO Pagos
VALUES (
        3,
        'Pago final proyecto 1',
        15000.00,
        '2025-04-20',
        2,
        1
    );
INSERT INTO Pagos
VALUES (
        4,
        'Pago único proyecto 4',
        12000.00,
        '2025-05-21',
        4,
        3
    );
INSERT INTO Pagos
VALUES (
        5,
        'Adelanto proyecto 3',
        7000.00,
        '2025-03-15',
        1,
        5
    );
-- Consultas de ejemplo inner join
SELECT c.RazonSocial AS Cliente,
    p.Descripcion AS Proyecto,
    col.NombreColaborador AS Colaborador,
    pc.IdProyectoColaborador AS ProyectoColaboradorId
FROM Clientes c
    INNER JOIN Proyectos p ON c.IdCliente = p.IdCliente
    INNER JOIN ProyectoColaborador pc ON p.IdProyecto = pc.IdProyecto
    INNER JOIN Colaboradores col ON pc.IdColaborador = col.IdColaborador
WHERE c.IdCliente = 1;
--  full outer join
SELECT c.RazonSocial AS Cliente,
    p.Descripcion AS Proyecto,
    col.NombreColaborador AS Colaborador,
    pc.IdProyectoColaborador AS ProyectoColaboradorId
FROM Clientes c
    FULL OUTER JOIN Proyectos p ON c.IdCliente = p.IdCliente
    FULL OUTER JOIN ProyectoColaborador pc ON p.IdProyecto = pc.IdProyecto
    FULL OUTER JOIN Colaboradores col ON pc.IdColaborador = col.IdColaborador
WHERE c.IdCliente = 1
    OR p.IdProyecto IS NULL
    OR col.IdColaborador IS NULL;
-- left outer join
SELECT c.RazonSocial AS Cliente,
    p.Descripcion AS Proyecto,
    col.NombreColaborador AS Colaborador,
    pc.IdProyectoColaborador AS ProyectoColaboradorId
FROM Clientes c
    LEFT OUTER JOIN Proyectos p ON c.IdCliente = p.IdCliente
    LEFT OUTER JOIN ProyectoColaborador pc ON p.IdProyecto = pc.IdProyecto
    LEFT OUTER JOIN Colaboradores col ON pc.IdColaborador = col.IdColaborador
WHERE c.IdCliente = 1;
-- right outer join
SELECT c.RazonSocial AS Cliente,
    p.Descripcion AS Proyecto,
    col.NombreColaborador AS Colaborador,
    pc.IdProyectoColaborador AS ProyectoColaboradorId
FROM Clientes c
    RIGHT OUTER JOIN Proyectos p ON c.IdCliente = p.IdCliente
    RIGHT OUTER JOIN ProyectoColaborador pc ON p.IdProyecto = pc.IdProyecto
    RIGHT OUTER JOIN Colaboradores col ON pc.IdColaborador = col.IdColaborador
WHERE p.IdProyecto = 1;
-- left anti join
SELECT c.RazonSocial AS Cliente,
    p.Descripcion AS Proyecto,
    col.NombreColaborador AS Colaborador,
    pc.IdProyectoColaborador AS ProyectoColaboradorId
FROM Clientes c
    LEFT JOIN Proyectos p ON c.IdCliente = p.IdCliente
    LEFT JOIN ProyectoColaborador pc ON p.IdProyecto = pc.IdProyecto
    LEFT JOIN Colaboradores col ON pc.IdColaborador = col.IdColaborador
WHERE c.IdCliente = 1
    AND p.IdProyecto IS NULL;
-- right anti join
SELECT c.RazonSocial AS Cliente,
    p.Descripcion AS Proyecto,
    col.NombreColaborador AS Colaborador,
    pc.IdProyectoColaborador AS ProyectoColaboradorId
FROM Clientes c
    RIGHT JOIN Proyectos p ON c.IdCliente = p.IdCliente
    RIGHT JOIN ProyectoColaborador pc ON p.IdProyecto = pc.IdProyecto
    RIGHT JOIN Colaboradores col ON pc.IdColaborador = col.IdColaborador
WHERE p.IdProyecto = 1
    AND c.IdCliente IS NULL;