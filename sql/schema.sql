-- SQL DDL approximated from Laravel migrations

CREATE TABLE `patients` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `rut` VARCHAR(255) NOT NULL UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `birth_date` DATE NOT NULL,
  `gender` VARCHAR(255) NOT NULL,
  `adress` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `email_verified_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE `diagnostics` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `description` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `patient_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `records` (
  `id_historial` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `patient_id` BIGINT UNSIGNED NOT NULL,
  `diagnostic_id` BIGINT UNSIGNED NOT NULL,
  `tratamientos` TEXT NOT NULL,
  `fecha` DATE NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`diagnostic_id`) REFERENCES `diagnostics`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `symptoms` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE `diagnostic_symptom` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `diagnostic_id` BIGINT UNSIGNED NOT NULL,
  `symptom_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`diagnostic_id`) REFERENCES `diagnostics`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`symptom_id`) REFERENCES `symptoms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `enfermedades` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE `enfermedad_record` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `enfermedad_id` BIGINT UNSIGNED NOT NULL,
  `record_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`enfermedad_id`) REFERENCES `enfermedades`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`record_id`) REFERENCES `records`(`id_historial`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `alergias` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE `alergia_record` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `alergia_id` BIGINT UNSIGNED NOT NULL,
  `record_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`alergia_id`) REFERENCES `alergias`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`record_id`) REFERENCES `records`(`id_historial`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `cirugias` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE `cirugia_record` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `cirugia_id` BIGINT UNSIGNED NOT NULL,
  `record_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  FOREIGN KEY (`cirugia_id`) REFERENCES `cirugias`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`record_id`) REFERENCES `records`(`id_historial`) ON DELETE CASCADE
) ENGINE=InnoDB;
