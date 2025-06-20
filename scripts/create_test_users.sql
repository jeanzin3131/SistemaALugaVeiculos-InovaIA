-- SQL script to create three test users: admin, locador, and locatario
INSERT INTO usuarios (nome, email, senha, telefone, tipo_usuario) VALUES
  ('Admin Teste', 'admin@teste.com', '$2b$12$ERS0ShjZ9IO07UYx0QAaOOL6mqBZeyT/6moPVMDE1eQzvhrChIKsi', '0000000000', 'admin'),
  ('Locador Teste', 'locador@teste.com', '$2b$12$yzXgtkzytCfJmh86uzbpjugXbyI9rKDPcx0Xwn/It6YMUT0N3IACu', '1111111111', 'locador'),
  ('Locatario Teste', 'locatario@teste.com', '$2b$12$vphNY6KNFLWn/MOWoIBJT.2GjZ5pBUQs3vnMvDJX8e6ACjm6nnp66', '2222222222', 'locatario');
