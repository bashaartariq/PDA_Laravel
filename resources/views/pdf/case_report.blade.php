<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Case Report</h1>
    
    <p><strong>Report Period:</strong> {{ $startDate }} to {{ $endDate }}</p>
    <p><strong>Doctor ID:</strong> {{ $DoctorId }}</p>
    <p><strong>Doctor Name:</strong> {{ $doctorName }}</p>

    <h2>Case Data</h2>
    <table>
        <thead>
            <tr>
                <th>Case Type</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($caseData as $case)
                <tr>
                    <td>{{ $case->case_type }}</td>
                    <td>{{ $case->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
