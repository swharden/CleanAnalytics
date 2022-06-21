Anonymize("../../htdocs/logs/2022-06-21.txt");

static string AnonymizeIp(string ip)
{
    string[] parts = ip.Split(".");
    return $"{parts[0]}.{parts[1]}.x.x";
}

static void Anonymize(string filePath)
{
    System.Text.StringBuilder sb = new();

    string[] lines = File.ReadAllLines(filePath);
    foreach (string line in lines)
    {
        string[] parts = line.Split(" ", 5);
        parts[1] = AnonymizeIp(parts[1]);
        string line2 = string.Join(" ", parts);
        sb.AppendLine(line2);
    }

    File.WriteAllText(filePath, sb.ToString());
}