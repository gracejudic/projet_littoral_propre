<?php
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Démarrer une transaction
        $pdo->beginTransaction();

        // 1. D'abord mettre à NULL les références dans la table collectes
        $stmt_update_collectes = $pdo->prepare("UPDATE collectes SET id_benevole = NULL WHERE id_benevole = ?");
        $stmt_update_collectes->execute([$id]);

        // 2. Ensuite, on peut supprimer le bénévole en toute sécurité
        $stmt_delete_benevole = $pdo->prepare("DELETE FROM benevoles WHERE id = ?");
        $stmt_delete_benevole->execute([$id]);

        // Valider la transaction
        $pdo->commit();

        header("Location: volunteer_list.php?success=1");
        exit();

    } catch (PDOException $e) {
        // En cas d'erreur, annuler les modifications
        $pdo->rollBack();
        die("Erreur: " . $e->getMessage());
    }
} else {
    echo "ID invalide.";
}
?>
