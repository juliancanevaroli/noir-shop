
CREATE DATABASE IF NOT EXISTS noir_shop
    CHARACTER SET utf8
    COLLATE utf8_general_ci;


USE noir_shop;

CREATE TABLE IF NOT EXISTS produtos (
    id          INT AUTO_INCREMENT PRIMARY KEY, 
    nome        VARCHAR(100)   NOT NULL,        
    descricao   TEXT,                            
    preco       DECIMAL(10,2)  NOT NULL,        
    categoria   VARCHAR(50)    NOT NULL,        
    imagem      VARCHAR(255),                    
    estoque     INT            NOT NULL DEFAULT 0,
    criado_em   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE IF NOT EXISTS pedidos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente    VARCHAR(100) NOT NULL,        
    email_cliente   VARCHAR(100) NOT NULL,        
    total       DECIMAL(10,2)  NOT NULL,         
    status      ENUM('pendente','confirmado','enviado','entregue')
                DEFAULT 'pendente',               
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS itens_pedido (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id   INT            NOT NULL,          
    produto_id  INT            NOT NULL,          
    quantidade  INT            NOT NULL,         
    preco_unit  DECIMAL(10,2)  NOT NULL,          

    FOREIGN KEY (pedido_id)  REFERENCES pedidos(id)  ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT
);


INSERT INTO produtos (nome, descricao, preco, categoria, imagem, estoque) VALUES
('Blazer Noir Classic',  'Blazer estruturado em tecido italiano, corte reto atemporal.', 890.00, 'Alfaiataria', 'blazerfashion.jpg',  10),
('Vestido Sombra',       'Vestido midi em crepe, decote sutil e caimento impecável.',    620.00, 'Vestidos',    'vestidofashion.jpg',  8),
('Bolsa Minuit',         'Bolsa de couro vegano com fecho dourado envelhecido.',         430.00, 'Acessórios',  'bolsafashioon.jpg',    5),
('Calça Palazzo',        'Calça de perna larga em tecido fluido, cintura alta.',         380.00, 'Calças',      'calcafashion.jpg',   12),
('Trench Coat Cendre',   'Trench coat clássico em gabardine com cinto ajustável.',      1200.00, 'Casacos',     'casacofashion.jpg',   6),
('Cachecol Lumière',     'Cachecol oversized em lã merino, tom creme e bege.',           190.00, 'Acessórios',  'cachecolfashion.jpg',15);


