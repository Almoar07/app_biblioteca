<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Libro;
use App\Models\Ejemplar;
use App\Models\Prestamo;
use App\Models\Estudiante;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\PrestamoService;

class LiveDashboard extends Component
{
    public $totalLibros;
    public $totalEjemplares;
    public $ejemplaresDisponibles;
    public $totalCategorias;
    public $totalEstudiantes;

    public $prestamosActivos;
    public $prestamosPendientes;
    public $prestamosVencidos;

    // Propiedades para el gráfico
    public $chartType = 'books_per_month'; // 'books_per_month' o 'overdue_loans_per_month'
    public $selectedYear;
    public $chartData = [];

    public function mount()
    {
        $this->loadStatistics();
        // Inicializar el año seleccionado al año actual si no está ya establecido
        if (is_null($this->selectedYear)) {
            $this->selectedYear = Carbon::now()->year;
        }
        $this->loadChartData(); // Cargar los datos del gráfico al montar el componente
        PrestamoService::verificarRetrasados();
    }

    public function loadStatistics()
    {
        $this->totalLibros = Libro::count();
        $this->totalEjemplares = Ejemplar::count();
        $this->ejemplaresDisponibles = Ejemplar::where('status', 'disponible')->count();
        $this->totalCategorias = Categoria::count();
        $this->totalEstudiantes = Estudiante::count();

        $this->prestamosActivos = Prestamo::where('estado', 'activo')->count();
        $this->prestamosPendientes = Prestamo::where('estado', 'pendiente')->count();
        $this->prestamosVencidos = Prestamo::where('estado', 'retrasado')
            ->whereDate('fecha_devolucion_esperada', '<', Carbon::today())
            ->count();
    }

    // Método que se ejecuta cuando cambia el tipo de gráfico
    public function updatedChartType()
    {
        $this->loadChartData();
    }

    // Método que se ejecuta cuando cambia el año seleccionado
    public function updatedSelectedYear()
    {
        $this->loadChartData();
    }

    // Método para cargar los datos del gráfico según el tipo y el año
    public function loadChartData()
    {
        $data = [];
        $labels = [];
        $backgroundColor = [];
        $borderColor = [];
        $chartLabel = '';

        // Inicializar un array con 0 para cada mes
        $monthlyCounts = array_fill(1, 12, 0);

        if ($this->chartType === 'books_per_month') {
            $chartLabel = 'Libros Prestados';
            $backgroundColor = 'rgba(75, 192, 192, 0.6)';
            $borderColor = 'rgba(75, 192, 192, 1)';

            $booksData = Prestamo::select(
                DB::raw('MONTH(fecha_prestamo) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->whereYear('fecha_prestamo', $this->selectedYear)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            foreach ($booksData as $item) {
                $monthlyCounts[$item->month] = $item->count;
            }
        } elseif ($this->chartType === 'overdue_loans_per_month') {
            $chartLabel = 'Préstamos Vencidos';
            $backgroundColor = 'rgba(255, 99, 132, 0.6)';
            $borderColor = 'rgba(255, 99, 132, 1)';

            $overdueData = Prestamo::select(
                DB::raw('MONTH(fecha_devolucion_esperada) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->where('estado', 'retrasado')
                ->whereDate('fecha_devolucion_esperada', '<', Carbon::today())
                ->whereYear('fecha_devolucion_esperada', $this->selectedYear)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            foreach ($overdueData as $item) {
                $monthlyCounts[$item->month] = $item->count;
            }
        }

        // Convertir los conteos mensuales a un array indexado desde 0 para Chart.js
        $data = array_values($monthlyCounts);

        $this->chartData = [
            'label' => $chartLabel,
            'data' => $data,
            'backgroundColor' => $backgroundColor,
            'borderColor' => $borderColor,
        ];

        // Emitir un evento para que el JavaScript en la vista actualice el gráfico
        $this->dispatch('chartDataUpdated', chartData: $this->chartData);
    }

    public function render()
    {

        // Últimos 5 préstamos (con estudiante y libro asociado al ejemplar)
        $prestamosRecientes = Prestamo::with([
            'estudiante',
            'ejemplar.libro'
        ])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $librosPopulares = Libro::withCount('prestamos')
            ->orderByDesc('prestamos_count')
            ->limit(10)
            ->get();

        return view('livewire.live-dashboard', [
            'prestamosRecientes' => $prestamosRecientes,
            'librosPopulares' => $librosPopulares,
            'chartData' => $this->chartData, // Asegúrate de pasar los datos del gráfico a la vista
        ]);
    }
}
