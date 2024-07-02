## Querys usadas no banco de dados:


CREATE DATABASE controle_finaceiro;


---------------------------------------
CREATE TABLE tbl_empresa (
    id_empresa INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL
);


CREATE TABLE tbl_conta_pagar (
    id_conta_pagar INT PRIMARY KEY AUTO_INCREMENT,
    valor DECIMAL(10, 2) NOT NULL,
    data_pagar DATE,
    pago TINYINT,
    id_empresa INT,
    FOREIGN KEY (id_empresa) REFERENCES tbl_empresa(id_empresa)
);

-------------------------------------

INSERT INTO tbl_empresa (nome)
VALUES ('Nokia');
