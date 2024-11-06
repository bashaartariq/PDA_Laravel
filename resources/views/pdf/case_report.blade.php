<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #444;
        }

        h1 {
            color: #0056b3;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            line-height: 1.5;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #0056b3;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e2e6ea;
        }

        .summary {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Case Report</h1>
    
    <div class="summary">
        <p><strong>Report Period:</strong> {{ $startDate }} to {{ $endDate }}</p>
        <p><strong>Doctor ID:</strong> {{ $DoctorId }}</p>
        <p><strong>Doctor Name:</strong> {{ $doctorName }}</p>
    </div>

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