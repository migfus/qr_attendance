<table>
  <thead>
    <tr>
      <td colspan="7" height="20" style="text-align: center; font-size: 14px; font-weight: bold;; border: 2px solid #333">Tasks Report</td>
    </tr>
    <tr>
      <td colspan="7" height="15" style="text-align: center; font-size: 12px; font-weight: bold;; border: 2px solid #333">Date {{ $date_start }} - {{ $date_end }} </td>
    </tr>

    <tr>
      <td width="20" colspan="5" style="font-weight: bold; border: 2px solid #333">Name: {{ $user_name }}</td>
      <td width="22" style="font-weight: bold; border: 2px solid #333">Date Time: {{ $now }}</td>
      <td width="20" style="font-weight: bold; border: 2px solid #333">No of Request: {{ $count }}</td>
    </tr>

    <tr>
      <th style="font-weight: bold; border: 2px solid #333;">Name</th>
      <th style="font-weight: bold; border: 2px solid #333;">Message</th>
      <th style="font-weight: bold; border: 2px solid #333;">Priority</th>
      <th style="font-weight: bold; border: 2px solid #333;">Status</th>
      <th style="font-weight: bold; border: 2px solid #333;">Group</th>
      <th style="font-weight: bold; border: 2px solid #333;">User Assigned</th>
      <th style="font-weight: bold; border: 2px solid #333;">Created At</th>
    </tr>
  </thead>
  <tbody>
    @foreach($tasks as $index => $task)
      @if((count($tasks) - 1) > $index)
        <tr>
          {{ $index }} - {{ count($tasks) - 1 }}
          <td width="20" style="border-left: 2px solid #333">{{ $task['task_template']['name'] }}</td>
          <td width="40">{{ $task['message'] }}</td>
          <td width="10" style="color: #{{ $task['task_priority']['color']}}">{{ $task['task_priority']['name'] }}</td>
          <td width="13" style="color: #{{ $task['task_status']['text_color']}}">{{ $task['task_status']['past_name'] }}</td>
          <td width="40">{{ $task['task_template']['group']['name'] }}</td>
          <td width="25">{{ $task['user_assigned']['name'] }}</td>
          <td width="20" style="border-right: 2px solid #333">{{ $task['created_at'] }}</td>
        </tr>
      @else
        <tr>
          {{ $index }} - {{ count($tasks) - 1 }}
          <td width="20" style="border-bottom: 2px solid #333; border-left: 2px solid #333">{{ $task['task_template']['name'] }}</td>
          <td width="40" style="border-bottom: 2px solid #333">{{ $task['message'] }}</td>
          <td width="10" style="color: #{{ $task['task_priority']['color']}}; border-bottom: 2px solid #333">{{ $task['task_priority']['name'] }}</td>
          <td width="13" style="color: #{{ $task['task_status']['text_color']}}; border-bottom: 2px solid #333">{{ $task['task_status']['past_name'] }}</td>
          <td width="40" style="border-bottom: 2px solid #333">{{ $task['task_template']['group']['name'] }}</td>
          <td width="25" style="border-bottom: 2px solid #333">{{ $task['user_assigned']['name'] }}</td>
          <td width="20" style="border-bottom: 2px solid #333; border-right: 2px solid #333">{{ $task['created_at'] }}</td>
        </tr>
      @endif
    @endforeach8
  </tbody>
</table>
