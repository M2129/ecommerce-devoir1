
CREATE TABLE commande (
    id SERIAL PRIMARY KEY,
    prix_final FLOAT NOT NULL,
    reduction_appliquee BOOLEAN NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
