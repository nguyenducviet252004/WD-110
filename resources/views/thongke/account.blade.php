<div class="card text-white bg-primary h-100">
    <div class="card-body">
        <h5 class="card-title">Số lượng người dùng mới</h5>

        <!-- Dòng 2: Người dùng mới theo bộ lọc -->
        <p class="card-text">
            @if ($data['filtered_count'] > 0)
                <span class="text-warning font-weight-bold">
                    Người dùng mới trong khoảng thời gian được lọc:
                </span>
                <span class="font-weight-bold text-warning">
                    {{ $data['filtered_count'] }} người
                </span>
            @else
                <span class="text-warning font-weight-bold">
                    Không có dữ liệu người dùng mới trong khoảng thời gian đã chọn.
                </span>
            @endif
        </p>


        <!-- Dòng 1: Người dùng mới trong tháng này -->
        <p class="card-text">Người dùng mới tháng này:
            <span class="font-weight-bold">{{ $data['current_count'] }}</span>
        </p>

        <!-- Dòng 3: Người dùng mới trong tháng trước -->
        <p class="card-text">Người dùng mới tháng trước:
            <span class="font-weight-bold">{{ $data['last_count'] }}</span>
        </p>
    </div>
</div>
