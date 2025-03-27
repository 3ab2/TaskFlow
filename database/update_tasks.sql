-- Ajouter la colonne due_date à la table tasks
ALTER TABLE tasks
ADD COLUMN due_date DATE AFTER priority;

-- Mettre à jour les tâches existantes avec une date d'échéance par défaut (30 jours après la création)
UPDATE tasks
SET due_date = DATE_ADD(created_at, INTERVAL 30 DAY)
WHERE due_date IS NULL; 