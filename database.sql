CREATE DATABASE IF NOT EXISTS dirigeai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dirigeai;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo_usuario ENUM('admin','locador','locatario') NOT NULL,
    collector_id VARCHAR(255),
    documentos_verificados TINYINT(1) DEFAULT 0,
    motivo_reprovacao VARCHAR(255)
) ENGINE=InnoDB;

-- Tabela de veículos
CREATE TABLE IF NOT EXISTS veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modelo VARCHAR(100) NOT NULL,
    marca VARCHAR(100) NOT NULL,
    ano INT NOT NULL,
    foto VARCHAR(255) NOT NULL,
    documento VARCHAR(255) NOT NULL,
    status ENUM('pendente','aprovado','rejeitado','ativo','inativo') DEFAULT 'pendente',
    valor_diaria DECIMAL(10,2) NOT NULL,
    locador_id INT NOT NULL,
    FOREIGN KEY (locador_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- Tabela de reservas
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    locatario_id INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    data_reserva DATETIME NOT NULL,
    status_reserva ENUM('pendente','aceita','rejeitada','confirmada','pago','cancelada') DEFAULT 'pendente',
    valor_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    FOREIGN KEY (locatario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- Tabela de documentos enviados pelos usuários
CREATE TABLE IF NOT EXISTS documentos_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_documento VARCHAR(100) NOT NULL,
    caminho_arquivo VARCHAR(255) NOT NULL,
    data_envio DATETIME NOT NULL,
    status ENUM('pendente','aprovado','rejeitado') DEFAULT 'pendente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- Tabela para registro simplificado de locadores (utilizada em mercadopago_registrar_locador.php)
CREATE TABLE IF NOT EXISTS locadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

