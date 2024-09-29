import os
import re

def remove_comments_from_file(file_path):
    # PHP und HTML Kommentare Regex
    single_line_comment = re.compile(r'//.*?$|<!--.*?-->|/\*.*?\*/', re.DOTALL | re.MULTILINE)
    multi_line_comment = re.compile(r'/\*.*?\*/', re.DOTALL)

    with open(file_path, 'r', encoding='utf-8', errors='ignore') as file:
        content = file.read()

    # Entferne Kommentare
    content_no_comments = re.sub(single_line_comment, '', content)
    content_no_comments = re.sub(multi_line_comment, '', content_no_comments)

    # Speichere die Datei ohne Kommentare
    with open(file_path, 'w', encoding='utf-8', errors='ignore') as file:
        file.write(content_no_comments)

def search_and_process_files(directory):
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith('.php') or file.endswith('.html'):
                file_path = os.path.join(root, file)
                print(f"Bearbeite Datei: {file_path}")
                remove_comments_from_file(file_path)

if __name__ == '__main__':
    current_directory = os.path.dirname(os.path.abspath(__file__))
    search_and_process_files(current_directory)
