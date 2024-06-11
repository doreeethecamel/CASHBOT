<?php  
function incrementInteractionCount($elementName, $pdo) {
    $sql = "UPDATE interaction_tracking SET interaction_count = interaction_count + 1, last_interaction_time = NOW() WHERE element_name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$elementName]);
}

function getInteractionCounts($pdo) {
    $sql = "SELECT element_name, interaction_count FROM interaction_tracking";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>