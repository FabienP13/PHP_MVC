<?php 

namespace App\Controller;

use App\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route(path: "/dashboard")]
    public function getDashboard() {
        echo $this->twig->render("dashboard/dashboard.html.twig");
    }
}
