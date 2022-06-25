foreach (string path in Directory.GetFiles("../../analytics/logs", "*.txt"))
{
    Console.WriteLine(path);
    Anonymize(path);
}

static string AnonymizeIp(string ip)
{
    if (ip.Contains("x")) // already anonymous
        return ip;

    string[] parts = ip.Split(".");
    parts[2] = "xx" + (int.Parse(parts[2]) % 10).ToString();
    parts[3] = "xx" + (int.Parse(parts[3]) % 10).ToString();
    return string.Join(".", parts);
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